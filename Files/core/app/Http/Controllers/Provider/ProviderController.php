<?php

namespace App\Http\Controllers\Provider;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Conversation;
use App\Models\Deposit;
use App\Models\Form;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ServiceOption;
use App\Models\TrackOrder;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProviderController extends Controller {

    public function dashboard() {
        $pageTitle = 'Dashboard';
        $provider  = auth()->guard('provider')->user();

        $withdrawQuery             = Withdrawal::query()->where('provider_id', $provider->id);
        $withdrawsLog              = (clone $withdrawQuery)->where('status', Status::PAYMENT_SUCCESS)->take(5)->get();
        $widget['total_withdrawn'] = (clone $withdrawQuery)->approved()->sum('amount');
        $ongoingWithdraws          = (clone $withdrawQuery)->pending()->latest()->take(5)->get();

        $orderQuery                 = Order::where('provider_id', $provider->id);
        $widget['total_earned']     = (clone $orderQuery)->where('status', Status::ORDER_COMPLETED)->sum('total');
        $widget['total_due']        = (clone $orderQuery)->whereIn('status', [Status::ORDER_PENDING, Status::ORDER_PROCESSING, Status::ORDER_COMPLETED_REQUEST])->sum('total');
        $orders                     = (clone $orderQuery)->latest()->take(5)->get();
        $widget['incomplete_order'] = (clone $orderQuery)->whereIn('status', [Status::ORDER_PENDING, Status::ORDER_PROCESSING, Status::ORDER_COMPLETED_REQUEST])->count();
        return view('Template::provider.dashboard', compact('pageTitle', 'provider', 'widget', 'orders', 'withdrawsLog', 'ongoingWithdraws'));
    }

    public function profile() {
        $pageTitle     = "Profile Setting";
        $provider      = auth()->guard('provider')->user();
        $completeOrder = Order::where('provider_id', $provider->id)->where('status', Status::ORDER_COMPLETED)->count();
        $totalEarned   = Order::where('provider_id', $provider->id)->where('status', Status::ORDER_COMPLETED)->sum('total');
        return view('Template::provider.profile_setting', compact('pageTitle', 'provider', 'completeOrder', 'totalEarned'));
    }

    public function submitProfile(Request $request) {
        $request->validate([
            'firstname' => 'required|string',
            'lastname'  => 'nullable|string',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'firstname.required' => 'The first name field is required',
        ]);

        $provider            = auth()->guard('provider')->user();
        $provider->firstname = $request->firstname;
        $provider->lastname  = $request->lastname;
        $provider->address   = $request->address;
        $provider->city      = $request->city;
        $provider->state     = $request->state;
        $provider->zip       = $request->zip;

        if ($request->hasFile('image')) {
            $path            = fileUploader($request->image, getFilePath('providerProfile'), getFileSize('providerProfile'), old('image'));
            $provider->image = $path;
            $provider->save();
        }

        $provider->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function kycForm() {
        if (auth()->guard('provider')->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('provider.dashboard')->withNotify($notify);
        }
        if (auth()->guard('provider')->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('provider.dashboard')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form      = Form::where('act', 'kyc')->first();
        return view('Template::provider.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData() {
        $provider  = auth()->guard('provider')->user();
        $pageTitle = 'KYC Data';
        abort_if($provider->kv == Status::VERIFIED, 403);
        return view('Template::provider.kyc.info', compact('pageTitle', 'provider'));
    }

    public function kycSubmit(Request $request) {
        $form           = Form::where('act', 'kyc')->firstOrFail();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $provider = auth()->guard('provider')->user();
        foreach ($provider->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $providerData                   = $formProcessor->processFormData($request, $formData);
        $provider->kyc_data             = $providerData;
        $provider->kyc_rejection_reason = null;
        $provider->kv                   = Status::KYC_PENDING;
        $provider->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('provider.dashboard')->withNotify($notify);
    }

    public function downloadAttachment($fileHash) {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title     = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function providerData() {
        $provider = auth()->guard('provider')->user();

        if ($provider->profile_complete == Status::YES) {
            return to_route('provider.dashboard');
        }

        $pageTitle  = 'Complete Your Profile';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = isset($info['code']) ?  implode(',', $info['code']) : '';
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::provider.provider_data', compact('pageTitle', 'provider', 'countries', 'mobileCode'));
    }

    public function providerDataSubmit(Request $request) {

        $provider = auth()->guard('provider')->user();

        if ($provider->profile_complete == Status::YES) {
            return to_route('provider.dashboard');
        }

        $countryData  = (array) json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:providers|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('providers')->where('dial_code', $request->mobile_code)],
        ]);

        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $provider->country_code     = $request->country_code;
        $provider->mobile           = $request->mobile;
        $provider->username         = $request->username;
        $provider->address          = $request->address;
        $provider->city             = $request->city;
        $provider->state            = $request->state;
        $provider->zip              = $request->zip;
        $provider->country_name     = $request->country;
        $provider->dial_code        = $request->mobile_code;
        $provider->profile_complete = Status::YES;
        $provider->save();

        return to_route('provider.dashboard');
    }

    public function changePassword() {
        $pageTitle = 'Change Password';
        return view('Template::provider.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request) {

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', $passwordValidation],
        ]);

        $user = auth()->guard('provider')->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password       = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changed successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }
    }

    public function workDetails() {
        $pageTitle  = 'Work Details';
        $provider   = auth()->guard('provider')->user();
        $cities     = City::active()->get();
        $areas      = Area::active()->get();
        $categories = Category::active()->get();

        return view('Template::provider.work_details', compact('pageTitle', 'provider', 'cities', 'areas', 'categories'));
    }

    public function show2faForm() {
        $ga        = new GoogleAuthenticator();
        $provider  = auth('provider')->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($provider->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';
        return view('Template::provider.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request) {
        $provider = auth('provider')->user();
        $request->validate([
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($provider, $request->code, $request->key);
        if ($response) {
            $provider->tsc = $request->key;
            $provider->ts  = Status::ENABLE;
            $provider->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request) {
        $request->validate([
            'code' => 'required',
        ]);

        $provider = auth('provider')->user();
        $response = verifyG2fa($provider, $request->code);
        if ($response) {
            $provider->tsc = null;
            $provider->ts  = Status::DISABLE;
            $provider->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function submitWorkDetails(Request $request) {

        $request->validate([
            'service_area_id'     => 'required|string',
            'service_category_id' => 'required|string',
        ], [
            'service_area_id.required'     => 'Service area field is required',
            'service_category_id.required' => 'Service type field is required',
        ]);

        $user                      = auth()->guard('provider')->user();
        $user->service_city_id     = $request->service_city_id;
        $user->service_area_id     = $request->service_area_id;
        $user->service_category_id = $request->service_category_id;
        $user->save();
        $notify[] = ['success', 'Work details updated successfully'];
        return back()->withNotify($notify);
    }

    public function findWork() {
        $pageTitle = 'Find Work';
        $provider  = auth()->guard('provider')->user();

        if ($provider->kv != Status::KYC_VERIFIED) {
            $notify[] = ['error', 'KYC Verification is required to proceed.'];
            return back()->withNotify($notify);
        }

        $orders = Order::where('status', Status::ORDER_PENDING)->where('area_id', $provider->service_area_id)
            ->whereHas('orderDetails.serviceOption.service', function ($query) use ($provider) {
                $query->where('category_id', $provider->service_category_id);
            })
            ->whereHas('orderDetails.serviceOption', function ($query) {
                $query->searchable(['name']);
            })
            ->where(function ($query) {
                $query->whereDoesntHave('deposit')
                    ->orWhereHas('deposit', function ($query) {
                        $query->where('status', Status::PAYMENT_SUCCESS);
                    });
            })
            ->latest('id')
            ->paginate(getPaginate())
            ->through(function ($order) {
                $orderDetails = OrderDetail::with(['serviceOption.service'])
                    ->where('order_id', $order->id)
                    ->get()
                    ->map(function ($detail) {
                        $serviceOption               = ServiceOption::find($detail->service_option_id);
                        $detail->service_option_name = $serviceOption ? $serviceOption->name : null;
                        return $detail;
                    });

                $order->details = $orderDetails;
                return $order;
            });

        return view('Template::provider.find_work', compact('pageTitle', 'provider', 'orders'));
    }

    public function orderAccept($order_id) {
        $provider = auth('provider')->user();
        $order    = Order::pending()->where('id', $order_id)->first();

        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return back()->withNotify($notify);
        }

        $order->status      = Status::ORDER_PROCESSING;
        $order->provider_id = $provider->id;
        $order->save();

        $this->trackOrder($order->id, $order->user_id, trans('Order has been accepted'));

        notify($order->user, 'ORDER_PROCESSING', [
            'trx'            => $order->trx,
            'order_number'   => $order->order_id,
            'user_full_name' => $order->user->fullname,
            'provider'       => $order->provider->fullname,
            'address'        => $order->user->address,
            'total_price'    => showAmount($order->total, currencyFormat: false),
            'payment_type'   => $order->payment_type == Status::ONLINE_PAYMENT ? trans('Online Payment') : trans('Cash On Delivery'),
            'payment_status' => $order->payment_status == Status::PAID ? trans('Paid') : trans('Unpaid'),
        ]);

        $notify[] = ['success', 'Order accepted successfully'];

        return to_route('provider.order.accepted', ['order_id' => $order_id])->withNotify($notify);
    }

    public function orderAccepted($order_id) {

        $provider = auth('provider')->user();
        $order    = Order::where('id', $order_id)->where('provider_id', $provider->id)->first();
        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Order accepted successfully'];
        return to_route('provider.service.details', ['order_id' => $order_id])->withNotify($notify);
    }

    public function serviceHistory() {

        $pageTitle = "Service History";
        $provider  = auth('provider')->user();
        $orders    = Order::where('provider_id', $provider->id)->latest()->searchable(['order_id'])->paginate(getPaginate());
        return view('Template::provider.service_history', compact('pageTitle', 'orders'));
    }

    public function serviceDetails($order_id) {

        $pageTitle = "Order Details";
        $provider  = auth()->guard('provider')->user();

        $order = Order::where('id', $order_id)->where('provider_id', $provider->id)->first();
        if (!$order) {
            $notify[] = ['error', 'Order not found'];
            return back()->withNotify($notify);
        }

        $orderDetails = OrderDetail::with(['serviceOption'])->where('order_id', $order_id)->get();

        $serviceName = $orderDetails->first()->serviceOption->service;

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();

        $orderPayment = Deposit::where('order_id', $order_id)->where('status', Status::PAYMENT_SUCCESS)->first();
        $gateWayInfo  = Gateway::where('code', $orderPayment?->method_code)->first();
        $conversation = Conversation::where('order_id', $order_id)->where('provider_id', $provider->id)->where('user_id', $order->user_id)->get();
        return view('Template::provider.service_details', compact('pageTitle', 'order', 'provider', 'orderDetails', 'serviceName', 'gatewayCurrency', 'orderPayment', 'gateWayInfo', 'conversation'));
    }

    public function orderCompleteRequest($order_id) {

        $provider = auth()->guard('provider')->user();
        $order    = Order::processing()->where('id', $order_id)->where('provider_id', $provider->id)->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        $order->status = Status::ORDER_COMPLETED_REQUEST;
        $order->save();

        $this->trackOrder($order->id, $order->user_id, trans('Sent order completion request from the provider'));

        notify($order->user, 'ORDER_COMPLETE_REQUESTED', [
            'trx'            => $order->trx,
            'order_number'   => $order->order_id,
            'user_full_name' => $order->user->fullname,
            'provider'       => $order->provider->fullname,
            'address'        => $order->user->address,
            'total_price'    => showAmount($order->total, currencyFormat: false),
            'payment_type'   => $order->payment_type == Status::ONLINE_PAYMENT ? trans('Online Payment') : trans('Cash On Delivery'),
            'payment_status' => $order->payment_status == Status::PAID ? trans('Paid') : trans('Unpaid'),
        ]);

        $notify[] = ['success', 'Send order completed request successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function transactions() {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('provider_id', auth()->guard('provider')->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::provider.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    protected function trackOrder($order_id, $user_id, $message) {

        $trackOrder           = new TrackOrder();
        $trackOrder->order_id = $order_id;
        $trackOrder->user_id  = $user_id;
        $trackOrder->message  = $message;
        $trackOrder->save();
    }
}
