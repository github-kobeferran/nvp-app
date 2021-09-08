<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

class Appointment extends Model
{
    use HasFactory;    

    public function service(){

        return $this->hasOne(Service::class, 'id', 'service_id');

    }

    public function pet(){

        return $this->hasOne(Pet::class, 'id', 'pet_id');

    }

}
