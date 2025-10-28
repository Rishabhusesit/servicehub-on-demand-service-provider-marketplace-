<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function provider() {
        return $this->belongsTo(Provider::class);
    }

    public function review() {
        return $this->hasOne(OrderReview::class);
    }
    public function deposit() {
        return $this->hasOne(Deposit::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function area() {
        return $this->belongsTo(Area::class);
    }

    public function orderDetails() {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function orderStatusBadge(): Attribute {
        return new Attribute(
            get: fn() => $this->orderStatus(),
        );
    }

    public function orderStatus() {
        $html = '';
        if ($this->status == Status::ORDER_PENDING) {
            $html = '<span class="badge--warning badge">' . trans('Pending') . '</span>';
        } else if ($this->status == Status::ORDER_PROCESSING) {
            $html = '<span class="badge--info badge">' . trans('Processing') . '</span>';
        } else if ($this->status == Status::ORDER_COMPLETED) {
            $html = '<span class="badge--success badge">' . trans('Completed') . '</span>';
        } else if ($this->status == Status::ORDER_COMPLETED_REQUEST) {
            $html = '<span class="badge--info badge">' . trans('Request for completed') . '</span>';
        } else if ($this->status == Status::ORDER_CANCEL) {
            $html = '<span class="badge--danger badge">' . trans('Cancelled') . '</span>';
        } else if ($this->status == Status::ORDER_REFUND) {
            $html = '<span class="badge--dark badge">' . trans('Request Refund') . '</span>';
        } else if ($this->status == Status::ORDER_REFUND_APPROVED) {
            $html = '<span class="badge--success badge">' . trans('Approved Refund') . '</span>';
        }
        return $html;
    }

    public function trackOrder() {
        return $this->hasMany(TrackOrder::class)->orderBy('id', 'desc');
    }

    public function paymentTypeBadge(): Attribute {
        return new Attribute(
            get: fn() => $this->paymentType(),
        );
    }

    public function paymentType() {
        $html = '';
        if ($this->payment_type == Status::COD_PAYMENT) {
            $html = '<span class="badge--success badge">' . trans('Cash On Delivery') . '</span>';
        } else if ($this->payment_type == Status::ONLINE_PAYMENT) {
            $html = '<span class="badge--warning badge">' . trans('Online Payment') . '</span>';
        }
        return $html;
    }

    public function paymentStatusBadge(): Attribute {
        return new Attribute(
            get: fn() => $this->paymentStatus(),
        );
    }

    public function paymentStatus() {
        $html = '';
        if ($this->payment_status == Status::PAID) {
            $html = '<span class="badge--success badge">' . trans('Paid') . '</span>';
        } else if ($this->payment_status == Status::UNPAID) {
            $html = '<span class="badge--warning badge">' . trans('Unpaid') . '</span>';
        }
        return $html;
    }

    public function scopePending() {
        return $this->where('status', Status::ORDER_PENDING);
    }

    public function scopeProcessing() {
        return $this->where('status', Status::ORDER_PROCESSING);
    }
    public function scopeRequested() {
        return $this->where('status', Status::ORDER_COMPLETED_REQUEST);
    }
    public function scopeCompleted() {
        return $this->where('status', Status::ORDER_COMPLETED);
    }
    public function scopeCancelled() {
        return $this->where('status', Status::ORDER_CANCEL);
    }

    public function scopePendingRefund() {
        return $this->where('status', Status::ORDER_REFUND);
    }

    public function scopeRefund() {
        return $this->where('status', Status::ORDER_REFUND)->orWhere('status', Status::ORDER_REFUND_APPROVED);
    }

}
