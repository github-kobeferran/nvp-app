<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PetType;
use App\Models\Client;

class Pet extends Model
{
    use HasFactory;

    protected $appends = ['dob_string' => null, 'age' => null];
    
    public function type()
    {
        return $this->belongsTo(PetType::class, 'pet_type_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function setDobStringAttribute($dob){

        $this->attributes['dob_string'] = \Carbon\Carbon::parse($dob)->isoFormat('MMM DD, OY');

    }

    public function getDobStringAttribute(){

        return $this->attributes['dob_string'];

    }

    public function setAgeAttribute($dob){

        $this->attributes['age'] = \Carbon\Carbon::parse($dob)->diff()->format('%y years, %m months and %d days');

    }

    public function getAgeAttribute(){

        return $this->attributes['age'];

    }

}
