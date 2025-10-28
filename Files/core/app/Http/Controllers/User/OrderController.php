<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Conversation;
use App\Models\Coupon;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderReview;
use App\Models\Provider;
use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\TrackOrder;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller {
    public function checkout(Request $request) {
        $pageTitle = "Checkout";
        $options   = session()->get('checkout_options', []);
        $subtotal  = session()->get('checkout_subtotal', 0);
        session()->forget('coupon_id');

        if ($request->option_id) {
            $options  = [];
            $subtotal = 0;

            $error = false;

            foreach ($request->option_id as $key => $optionId) {
                $option = ServiceOption::active()->with(['service', 'parent'])->with(['service' => function ($query) {
                    $query->with('offers');
                }])->find($optionId);

                if (!$option) {
                    $error = true;
                    break;
                }

                $offer         = $option?->service?->offers?->first();
                $discountPrice = $originalPrice = $option->price;

                if ($offer) {
                    if ($offer->discount_type == Status::DISCOUNT_PERCENT && $offer->amount > 0) {
                        $discountPrice = $originalPrice - $originalPrice * ($offer->amount / 100);
                    } else if ($offer->discount_type == Status::DISCOUNT_FIXED && $offer->amount > 0) {
                        $discountPrice = $originalPrice - $offer->amount;
                    }
                }

                if ($discountPrice > 0) {
                    $option->price = $discountPrice;
                }

                if ($option) {
                    $quantity = $request->quantity[$key];
                    $subtotal += $option->price * $quantity;

                    $options[] = [
                        'id'       => $option->id,
                        'name'     => $option->name,
                        'price'    => isset($option->price) ? $option->price : 0,
                        'service'  => $option->service->name,
                        'parent'   => implode(' - ', $option->all_parents),
                        'quantity' => $quantity,
                    ];
                }
            }

            session()->put('checkout_options', $options);
            session()->put('checkout_subtotal', $subtotal);
        }

        if ($error) {
            $options  = session()->get('checkout_options', []);
            $subtotal = session()->get('checkout_subtotal', 0);
            $notify[] = ['error', 'Some of the selected services are not available'];
            return to_route('home')->withNotify($notify);
        }

        if (!$options) {
            $notify[] = ['error', 'Please select your services'];
            return to_route('home')->withNotify($notify);
        }

        $cities = City::active()->with(['areas' => function ($query) {
            $query->active();
        }])->orderBy('name')->get();

        return view('Template::user.checkout', compact('pageTitle', 'cities', 'options', 'subtotal'));
    }

    public function applyCoupon(Request $request) {

        $couponCode = $request->coupon_code;
        $userId     = auth()->id();

        if (session('coupon_id')) {
            return response()->json([
                'success' => false,
                'message' => trans('A coupon is already applied.'),
            ]);
        }

        $coupon = Coupon::where('code', $couponCode)->where('status', Status::ACTIVE)->first();
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => trans('The coupon code you entered is invalid.'),
            ]);
        }

        $expire = Coupon::where('code', $couponCode)->where('status', Status::ACTIVE)->whereDate('start_date', '<=', now())->whereDate('end_date', '>=', now())->first();
        if (!$expire) {
            return response()->json([
                'success' => false,
                'message' => trans('This coupon has expired or is not currently active'),
            ]);
        }

        $subtotal = Session::get('checkout_subtotal', 0);
        if ($subtotal < $coupon->minimum_spend) {
            return response()->json([
                'success' => false,
                'message' => trans('Minimum spend of :amount required.', ['amount' => showAmount($coupon->minimum_spend)]),
            ]);
        }

        $totalUses = Order::where('coupon_id', $coupon->id)->count();
        if (!is_null($coupon->usage_limit_per_coupon) && $totalUses >= $coupon->usage_limit_per_coupon) {
            return response()->json([
                'success' => false,
                'message' => trans('This coupon has reached its usage limit.'),
            ]);
        }

        $userUses = Order::where('coupon_id', $coupon->id)->where('user_id', $userId)->count();
        if (!is_null($coupon->usage_limit_per_user) && $userUses >= $coupon->usage_limit_per_user) {
            return response()->json([
                'success' => false,
                'message' => trans('You have reached your usage limit for this coupon.'),
            ]);
        }

        if ($coupon->discount_type == Status::DISCOUNT_PERCENT) {
            $discount = $subtotal * $coupon->amount / 100;
        } else {
            $discount = $coupon->amount;
        }
        session()->put('coupon_id', $coupon->id);
        return response()->json([
            'success'  => true,
            'discount' => getAmount($discount),
            'message'  => trans('Coupon applied successfully.'),
        ]);
    }

    public function removeCoupon() {
        session()->forget('coupon_id');
        return response()->json([
            'success' => trans('Coupon removed successfully.'),
        ]);
    }

    public function orderPlace(Request $request) {
        $request->validate(
            [
                'from_time'     => 'required',
                'schedule_date' => 'required|date|after_or_equal:today',
                'city_id'       => 'required|integer|exists:cities,id',
                'area_id'       => 'required|integer|exists:areas,id',
                'address'       => 'required|string',
            ],
            [
                'city_id.required' => 'City field is required',
                'area_id.required' => 'Area field is required',
            ]
        );

        $services = Session::get('checkout_options');
        if (empty($services)) {
            $notify[] = ['error', 'Service not found'];
            return back()->withNotify($notify);
        }

        if (!$this->validateServices($services)) {
            $notify[] = ['error', 'Something went wrong'];
            return to_route('home')->withNotify($notify);
        }

        $subtotal = collect($services)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $city = City::active()->where('id', $request->city_id)->first();
        if (!$city) {
            $notify[] = ['error', 'City not found'];
            return back()->withNotify($notify);
        }

        $area = Area::where('id', $request->area_id)->active()->first();
        if (!$area) {
            $notify[] = ['error', 'Area not found'];
            return back()->withNotify($notify);
        }

        $deliveryCharge = $city->delivery_charge;
        $discount       = 0;

        $coupon = Coupon::where('id', session('coupon_id'))->where('status', Status::ACTIVE)->whereDate('start_date', '<=', now())->whereDate('end_date', '>=', now())->first();
        if ($coupon && $subtotal > $coupon->minimum_spend) {
            $totalUses = Order::where('coupon_id', session('coupon_id'))->count();
            $userUses  = Order::where('coupon_id', $coupon->id)->where('user_id', auth()->id())->count();
            if ($totalUses >= $coupon->usage_limit_per_coupon && $userUses >= $coupon->usage_limit_per_user) {
                $discount = 0;
            } else {
                if ($coupon->discount_type == Status::DISCOUNT_PERCENT) {
                    $discount = $subtotal * $coupon->amount / 100;
                } else {
                    $discount = $coupon->amount;
                }
            }
        }

        $total = ($subtotal + $deliveryCharge) - $discount;
        if ($total < 0) {
            $total = 0;
        }

        $order                        = new Order();
        $order->order_id              = getTrx();
        $order->user_id               = auth()->id();
        $order->schedule_time         = $request->from_time;
        $order->schedule_date         = $request->schedule_date;
        $order->contact_person_name   = $request->contact_person_name;
        $order->contact_person_number = $request->contact_person_number;
        $order->city_id               = $city->id;
        $order->area_id               = $area->id;
        $order->address               = $request->address;
        $order->sub_total             = $subtotal;
        $order->delivery_charge       = $deliveryCharge;
        $order->discount              = $discount;
        $order->total                 = $total;
        $order->coupon_id             = session('coupon_id') ?? 0;
        $order->note                  = $request->note;
        $order->payment_type          = $request->payment_type;
        $order->trx                   = getTrx();
        $order->save();

        foreach ($services as $service) {
            $oderServices                    = new OrderDetail();
            $oderServices->order_id          = $order->id;
            $oderServices->service_option_id = $service['id'];
            $oderServices->qty               = $service['quantity'];
            $oderServices->price             = isset($service['price']) ? $service['price'] : 0;
            $oderServices->subtotal          = $oderServices->price * $service['quantity'];
            $oderServices->save();
        }

        session()->forget('checkout_options');
        session()->forget('coupon_id');

        Provider::where('service_city_id', $request->city_id)->where('service_area_id', $request->area_id)->update(['send_email_for_order' => $order->id]);

        notify($order->user, 'ORDER_PLACE', [
            'trx'            => $order->trx,
            'order_number'   => $order->order_id,
            'user_full_name' => $order->user->fullname,
            'address'        => $order->user->address,
            'total_price'    => showAmount($order->total, currencyFormat: false),
            'payment_type'   => $order->payment_type == Status::ONLINE_PAYMENT ? trans('Online Payment') : trans('Cash On Delivery'),
            'payment_status' => $order->payment_status == Status::PAID ? trans('Paid') : trans('Unpaid'),
        ]);

        if ($order->payment_type == Status::COD_PAYMENT) {
            $notify[] = ['success', 'Order place successfully'];
            return to_route('user.order.details', ['order_id' => $order->id])->withNotify($notify);
        } else {
            $notify[] = ['success', 'Please confirm your payment'];
            return to_route('user.order.payment', ['order_id' => $order->id])->withNotify($notify);
        }
    }

    public function validateServices($services) {
        foreach ($services as $service) {
            if (empty($service['id']) || !isset($service['quantity']) || $service['quantity'] <= 0) {
                return false;
            }
        }
        return true;
    }

    public function orderPayment($order_id) {
        $pageTitle = "Order Payment";
        $user      = auth()->user();
        $order     = Order::pending()->where('id', $order_id)->where('user_id', $user->id)->with('orderDetails')->first();
        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return to_route('user.order.history')->withNotify($notify);
        }

        $orderDetails    = $order->orderDetails;
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();
        return view('Template::user.order_payment', compact('pageTitle', 'user', 'order', 'orderDetails', 'gatewayCurrency'));
    }

    public function orderHistory() {
        $pageTitle = "Order History";
        $orders    = Order::where('user_id', auth()->id())->orderBy('id', 'desc')->searchable(['order_id', 'total'])->with('orderDetails.service')->paginate(getPaginate());
        return view('Template::user.order_history', compact('pageTitle', 'orders'));
    }

    public function orderDetails($order_id) {
        $pageTitle = "Order Details";
        $user      = auth()->user();
        $order     = Order::where('id', $order_id)->where('user_id', $user->id)->with('orderDetails')->first();
        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return to_route('user.order.history')->withNotify($notify);
        }

        $orderDetails = $order->orderDetails;
        $serviceName  = $orderDetails->first()->serviceOption->service;

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();

        $orderPayment = Deposit::where('order_id', $order_id)->where('user_id', $user->id)->where(function ($query) {
            $query->where('status', Status::PAYMENT_SUCCESS)->orWhere('status', Status::PAYMENT_PENDING);
        })->first();
        $gateWayInfo  = Gateway::where('code', $orderPayment?->method_code)->first();
        $conversation = Conversation::where('order_id', $order_id)->where('user_id', $user->id)->where('provider_id', $order->provider_id)->get();
        return view('Template::user.order_details', compact('pageTitle', 'order', 'user', 'orderDetails', 'serviceName', 'gatewayCurrency', 'orderPayment', 'gateWayInfo', 'conversation'));
    }

    public function orderCancel($order_id) {
        $order = Order::pending()->where('user_id', auth()->id())->where('id', $order_id)->first();
        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return to_route('user.order.history')->withNotify($notify);
        }

        $order->status = Status::ORDER_CANCEL;
        $order->save();

        $this->trackOrder($order->id, trans('Order has been cancelled'));
        notify($order->user, 'ORDER_CANCEL', [
            'trx'            => $order->trx,
            'order_number'   => $order->order_id,
            'user_full_name' => $order->user->fullname,
            'address'        => $order->user->address,
            'total_price'    => showAmount($order->total, currencyFormat: false),
            'payment_type'   => $order->payment_type == Status::ONLINE_PAYMENT ? trans('Online Payment') : trans('Cash On Delivery'),
            'payment_status' => $order->payment_status == Status::PAID ? trans('Paid') : trans('Unpaid'),
        ]);

        $notify[] = ['success', 'Order has been cancelled'];
        return to_route('user.order.history')->withNotify($notify);
    }

    public function orderRefund($order_id) {
        $order = Order::where('user_id', auth()->id())->where('id', $order_id)->first();

        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return to_route('user.order.history')->withNotify($notify);
        }

        $order->status = Status::ORDER_REFUND;
        $order->save();

        $this->trackOrder($order->id, trans('Order has been Refund'));
        notify($order->user, 'ORDER_REFUND', [
            'trx'            => $order->trx,
            'order_number'   => $order->order_id,
            'user_full_name' => $order->user->fullname,
            'address'        => $order->user->address,
            'total_price'    => showAmount($order->total, currencyFormat: false),
        ]);

        $notify[] = ['success', 'Order refund request has been sent'];
        return to_route('user.order.history')->withNotify($notify);
    }

    public function orderRequestedApprove($order_id) {

        $order = Order::where('user_id', auth()->id())->where('id', $order_id)->first();
        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return to_route('user.order.history')->withNotify($notify);
        }

        $order->status         = Status::ORDER_COMPLETED;
        $order->payment_status = Status::PAID;
        $order->save();

        $provider = Provider::where('id', $order->provider_id)->firstOrFail();

        $amount           = $order->total;
        $fixedCharge      = gs('commission_fixed_charge');
        $percentageCharge = ($amount * gs('commission_percentage_charge')) / 100;
        $totalCharge      = $fixedCharge + $percentageCharge;

        if ($order->payment_type == Status::ONLINE_PAYMENT) {
            $provider->balance += $amount;
            $provider->save();

            $transaction               = new Transaction();
            $transaction->provider_id  = $order->provider_id;
            $transaction->amount       = $amount;
            $transaction->post_balance = $provider->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '+';
            $transaction->details      = showAmount($amount) . ' payment received for service';
            $transaction->trx          = $order->trx;
            $transaction->remark       = 'received_order_payment';
            $transaction->save();
        }

        $provider->balance -= $totalCharge;
        $provider->save();

        $transaction               = new Transaction();
        $transaction->provider_id  = $order->provider_id;
        $transaction->amount       = $totalCharge;
        $transaction->post_balance = $provider->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Commission Charge ' . showAmount($totalCharge);
        $transaction->trx          = $order->trx;
        $transaction->remark       = 'commission_charge';
        $transaction->save();

        $this->trackOrder($order->id, trans('Order completed successfully'));

        notify($order->user, 'ORDER_COMPLETED', [
            'trx'            => $order->trx,
            'order_number'   => $order->order_id,
            'user_full_name' => $order->user->fullname,
            'provider'       => $order->provider->fullname,
            'address'        => $order->user->address,
            'total_price'    => showAmount($order->total, currencyFormat: false),
            'payment_type'   => $order->payment_type == Status::ONLINE_PAYMENT ? trans('Online Payment') : trans('Cash On Delivery'),
            'payment_status' => $order->payment_status == Status::PAID ? trans('Paid') : trans('Unpaid'),
        ]);

        $notify[] = ['success', 'Order completed successfully'];
        return to_route('user.order.complete', ['order_id' => $order_id])->withNotify($notify);
    }

    public function orderComplete($order_id) {
        $pageTitle = "Order Complete";
        $order     = Order::where('id', $order_id)->where('user_id', auth()->id())->first();

        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return to_route('user.order.history')->withNotify($notify);
        }

        if ($order->review_status == Status::YES) {
            $notify[] = ['error', 'You have already rated this order.'];
            return to_route('user.order.history')->withNotify($notify);
        }
        return view('Template::user.order_complete', compact('pageTitle', 'order'));
    }

    public function orderReview($order_id) {
        $pageTitle = "Order Review";
        $user      = auth()->user();
        $order     = Order::where('id', $order_id)->where('user_id', auth()->id())->first();

        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return to_route('user.order.history')->withNotify($notify);
        }
        if ($order->review_status == Status::YES) {
            $notify[] = ['error', 'You have already rated this order.'];
            return to_route('user.order.history')->withNotify($notify);
        }

        $orderDetails = OrderDetail::with(['serviceOption'])->where('order_id', $order_id)->get();
        $serviceName  = $orderDetails->first()->serviceOption->service;
        return view('Template::user.order_review', compact('pageTitle', 'order', 'user', 'orderDetails', 'serviceName'));
    }

    public function orderReviewSubmit(Request $request, $order_id) {
        $request->validate(
            [
                'rating' => 'required|integer|between:1,5',
                'review' => 'required|string|max:500',
            ],
            [
                'rating.required' => 'Please provide a rating.',

            ]
        );

        $user  = auth()->user();
        $order = Order::where('id', $order_id)->where('user_id', $user->id)->first();

        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return to_route('user.order.history')->withNotify($notify);
        }

        $orderDetail   = OrderDetail::with(['serviceOption'])->where('order_id', $order_id)->first();
        $serviceOption = $orderDetail->serviceOption;
        $service       = $serviceOption->service;

        $existingRating = OrderReview::where('order_id', $order_id)->where('user_id', $user->id)->exists();

        if ($existingRating) {
            $notify[] = ['error', 'You have already rated this order.'];
            return back()->withNotify($notify);
        }

        $rating              = new OrderReview();
        $rating->order_id    = $order_id;
        $rating->provider_id = $order->provider_id;
        $rating->user_id     = $user->id;
        $rating->rating      = $request->rating;
        $rating->review      = $request->review;
        $rating->service_id  = $service->id;
        $rating->save();

        $order->review_status = Status::YES;
        $order->save();

        $averageRating            = OrderReview::where('provider_id', $order->provider_id)->avg('rating');
        $provider                 = Provider::findOrFail($order->provider_id);
        $provider->average_rating = $averageRating;
        $provider->save();

        $service                 = Service::findOrFail($service->id);
        $service->average_rating = OrderReview::where('service_id', $service->id)->avg('rating');
        $service->total_rating   = OrderReview::where('service_id', $service->id)->count();
        $service->save();

        notify($order->user, 'ORDER_REVIEW', [
            'order_number' => $order->order_id,
            'user'         => $order->user->fullname,
            'provider'     => $order->provider->fullname,
            'rating'       => $rating->rating,
            'review'       => $rating->review,
        ]);

        $notify[] = ['success', 'Thank you for your feedback!'];
        return to_route('user.order.history')->withNotify($notify);
    }

    protected function trackOrder($order_id, $message) {
        $trackOrder           = new TrackOrder();
        $trackOrder->user_id  = auth()->id();
        $trackOrder->order_id = $order_id;
        $trackOrder->message  = $message;
        $trackOrder->save();
    }
}
