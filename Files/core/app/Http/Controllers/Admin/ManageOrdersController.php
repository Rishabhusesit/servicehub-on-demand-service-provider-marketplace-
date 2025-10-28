<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TrackOrder;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ManageOrdersController extends Controller {
    public function allOrders() {
        $pageTitle = 'All Orders';
        $orders    = $this->orderData();
        return view('admin.orders.list', compact('pageTitle', 'orders'));
    }

    public function pendingOrders() {
        $pageTitle = 'Pending Orders';
        $orders    = $this->orderData('pending');
        return view('admin.orders.list', compact('pageTitle', 'orders'));
    }

    public function processingOrders() {
        $pageTitle = 'Processing Orders';
        $orders    = $this->orderData('processing');
        return view('admin.orders.list', compact('pageTitle', 'orders'));
    }

    public function requestedOrders() {
        $pageTitle = 'Requested Orders';
        $orders    = $this->orderData('requested');
        return view('admin.orders.list', compact('pageTitle', 'orders'));
    }

    public function completedOrders() {
        $pageTitle = 'Completed Orders';
        $orders    = $this->orderData('completed');
        return view('admin.orders.list', compact('pageTitle', 'orders'));
    }

    public function canceledOrders() {
        $pageTitle = 'Canceled Orders';
        $orders    = $this->orderData('cancelled');
        return view('admin.orders.list', compact('pageTitle', 'orders'));
    }

    public function refundOrders() {
        $pageTitle = 'Refund Orders';
        $orders    = $this->orderData('refund');
        return view('admin.orders.list', compact('pageTitle', 'orders'));
    }

    protected function orderData($scope = null) {
        if ($scope) {
            $ordersQuery = Order::$scope();
        } else {
            $ordersQuery = Order::query();
        }
        return $ordersQuery->latest('id')->searchable(['order_id', 'total', 'user:username', 'provider:username'])->filter(['payment_type', 'payment_status'])->dateFilter()->paginate(getPaginate());
    }

    public function acceptRefund(Request $request, $id = 0) {
        $order = Order::where('id', $id)->where('status', '!=', Status::ORDER_COMPLETED)->first();
        if (!$order) {
            $notify[] = ['error', 'Invalid request'];
            return back()->withNotify($notify);
        }

        $order->status = Status::ORDER_REFUND_APPROVED;
        $order->save();

        notify($order->user, 'ORDER_REFUND_APPROVED', [
            'trx'            => $order->trx,
            'order_number'   => $order->order_id,
            'user_full_name' => $order->user->fullname,
            'address'        => $order->user->address,
            'total_price'    => showAmount($order->total, currencyFormat: false),
            'refund_amount'  => showAmount($order->refund_amount, currencyFormat: false),
            'remark'         => $order->remark,
        ]);

        $notify[] = ['success', 'Refund request accepted successfully'];
        return back()->withNotify($notify);
    }
    public function complete(Request $request, $id = 0) {
        $order = Order::where('id', $id)->where('status', '!=', Status::ORDER_COMPLETED)->first();
        if (!$order) {
            $notify[] = ['error', 'Invalid request'];
            return back()->withNotify($notify);
        }

        $order->status = Status::ORDER_COMPLETED;
        $order->save();

        $amount           = $order->total;
        $fixedCharge      = gs('commission_fixed_charge');
        $percentageCharge = ($amount * gs('commission_percentage_charge')) / 100;
        $totalCharge      = $fixedCharge + $percentageCharge;

        $provider = $order->provider;

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

        $trackOrder           = new TrackOrder();
        $trackOrder->user_id  = $order->user_id;
        $trackOrder->order_id = $order->id;
        $trackOrder->message  = 'Order completed successfully';
        $trackOrder->save();

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
        return back()->withNotify($notify);
    }

}
