<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_category', function (Blueprint $table) {            
            $table->id();
            $table->string('desc');            
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('desc');
            $table->integer('item_category_id')->default(0);            
            $table->integer('pet_type_id')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->float('deal_price', 8, 2)->nullable();
            $table->float('reg_price', 8, 2);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('category');
    }
}
