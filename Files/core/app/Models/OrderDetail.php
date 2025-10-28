<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    public function serviceOption()
    {

        return  $this->belongsTo(ServiceOption::class);
    }


    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
