<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function category(){

        return $this->belongsTo(ItemCategory::class, 'item_category_id', 'id');

    }

    public function type(){

        return $this->belongsTo(PetType::class, 'pet_type_id', 'id');

    }

}
