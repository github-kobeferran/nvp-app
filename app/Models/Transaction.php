<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Payment;

class Transaction extends Model
{
    use HasFactory;

    public function client(){

        return $this->belongsTo(Client::class);

    }

    public function payment(){

        return $this->hasOne(Payment::class);

    }

}
