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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('country');
            $table->foreignId('audan_id')->on('audans')->cascadeOnDelete();
            $table->foreignId('user_id')->on('users')->cascadeOnDelete();
            $table->foreignId('curator_id')->on('users')->cascadeOnDelete();
            $table->string('project_type');
            $table->integer('invest_sum');
            $table->integer('people_count');
            $table->string('phone');
            $table->string('curator');
            $table->text('file');
            $table->json('images')->nullable();
            $table->integer('is_archived')->default(0);
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
        Schema::dropIfExists('projects');
    }
};
