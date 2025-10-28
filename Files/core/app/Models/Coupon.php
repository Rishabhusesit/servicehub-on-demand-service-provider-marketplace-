<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use GlobalStatus;


    public function services()
    {
        return $this->belongsToMany(Service::class, 'coupons_services');
    }

    public function getCouponTypeAttribute()
    {
        if($this->discount_type == 1){
            return 'Fixed';
        }else{
            return 'Percentage';
        }
    }


}
