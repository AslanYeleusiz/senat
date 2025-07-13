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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users')->cascadeOnDelete();
            $table->foreignId('project_id')->on('projects')->cascadeOnDelete();
            $table->string('title');
            $table->string('question');
            $table->string('answer')->nullable();
            $table->date('limit_date');
            $table->date('answer_date')->nullable();
            $table->integer('answer_user_id')->nullable();
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
