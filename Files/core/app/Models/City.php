<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use GlobalStatus;

    public function areas(){

        return $this->hasMany(Area::class);
    
    }

    public function scopeActive()
    {
        return $this->where('status', Status::ENABLE);
    }


}
