<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('lat');
            $table->decimal('lng');
            $table->string('iconUrl')->default('/images/murk.png');
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audans');
    }
};
