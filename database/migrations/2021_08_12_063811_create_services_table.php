<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('desc', 100);
            $table->unsignedInteger('price');
            $table->timestamps();
        });      

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();            
            $table->integer('client_id');
            $table->integer('service_id');            
            $table->dateTime('date_time');   
            $table->boolean('done')->default(0);
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
        Schema::dropIfExists('services');
        Schema::dropIfExists('appointments');
    }
}
