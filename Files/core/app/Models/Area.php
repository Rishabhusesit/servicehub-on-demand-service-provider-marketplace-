<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use GlobalStatus;
    
    public function city(){
        return $this->belongsTo(City::class);
    }
}
