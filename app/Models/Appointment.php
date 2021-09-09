<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Service;
use App\Models\Pet;

class Appointment extends Model
{
    use HasFactory;    
 
    public function pet(){

        return $this->hasOne(Pet::class, 'id', 'pet_id');

    }

    public function services(){

        $appointment_services = AppointmentServices::where('appointment_id', $this->id)->get();
                       
        $services = collect(new Service);
        
        foreach($appointment_services as $appointment_service){

            $services->push(Service::find($appointment_service->service_id));
            
        }            

        return $services;

    }

}
