<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Service extends Model {
    use GlobalStatus;

    public function faqs() {
        return $this->hasMany(Faq::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function serviceOptions() {
        return $this->hasMany(ServiceOption::class)->where('status', Status::ENABLE);
    }
    public function offers() {
        return $this->belongsToMany(Offer::class, 'offers_services')
            ->where('offers.start_date', '<=', now())
            ->where('offers.end_date', '>=', now())
            ->where('offers.status', Status::YES)
            ->orderBy('offers.created_at', 'desc');
    }

}
