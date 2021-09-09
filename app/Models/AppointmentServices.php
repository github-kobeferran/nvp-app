<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Appointment;
use App\Models\Service;

class AppointmentServices extends Pivot
{
    
    public $timestamps = false;

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }

}
