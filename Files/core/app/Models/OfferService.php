<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OfferService extends Model
{
    public function activeOffer()
    {
        return $this->belongsTo(Offer::class, 'offer_id')->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())->where('status', 1);
    }

    public function services()
    {
        return $this->belongsTo(Service::class);
    }
}
