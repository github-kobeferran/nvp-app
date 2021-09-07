<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pet;

class Client extends Model
{
    use HasFactory;

    protected $appends = ['dob_string' => null, 'age' => null];

    protected $fillable = [
        'user_id',        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->hasMany(Pet::class);
    }

    public function setDobStringAttribute($dob){

        $this->attributes['dob_string'] = \Carbon\Carbon::parse($dob)->isoFormat('MMM DD, OY');

    }

    public function getDobStringAttribute(){

        return $this->attributes['dob_string'];

    }

    public function setAgeAttribute($dob){

        $this->attributes['age'] = \Carbon\Carbon::parse($dob)->age;

    }

    public function getAgeAttribute(){

        return $this->attributes['age'];

    }

    public static function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
