<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class ServiceOption extends Model {
    use GlobalStatus;

    public function faqs() {
        return $this->hasMany(Faq::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function parent() {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function child() {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function getAllParentsAttribute() {
        $parents = [];
        $current = $this->parent;

        while ($current) {
            $parents[] = $current->name;
            $current   = $current->parent;
        }

        return array_reverse($parents);
    }

    public function serviceOptions() {
        return $this->hasMany(self::class, 'parent_id')->where('status', Status::ENABLE);
    }

}
