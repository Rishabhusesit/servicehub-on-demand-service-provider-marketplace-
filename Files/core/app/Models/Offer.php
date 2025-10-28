<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use GlobalStatus;

    public function services()
    {
        return $this->belongsToMany(Service::class, 'offers_services');
    }

    public static function scopeActive($query)
    {
        return $query->whereDate('start_date', '<=', today())
            ->where('status', 1);
    }

    public function getOfferTypeAttribute()
    {
        if ($this->discount_type == 1) {
            return 'Fixed';
        } else {
            return 'Percentage';
        }
    }
}
