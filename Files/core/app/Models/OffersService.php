<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffersService extends Model
{
    protected $table = 'offers_products';
    protected $guarded = ['id'];

    public function activeOffer()
    {
        return $this->belongsTo(Offer::class, 'offer_id')->where('start_date', '<=', \Carbon\Carbon::now())->where('end_date', '>=', \Carbon\Carbon::now())->where('status', 1);
    }

    public function services()
    {
        return $this->belongsTo(Service::class);
    }
}
