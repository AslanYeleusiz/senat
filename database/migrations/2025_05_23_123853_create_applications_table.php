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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->on('projects')->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('send_from')->on('user')->cascadeOnDelete();
            $table->foreignId('send_to')->on('user')->cascadeOnDelete();
            $table->string('investor')->nullable();
            $table->string('document_link')->nullable();
            $table->string('file_link')->nullable();
            $table->string('autobiography_link')->nullable();
            $table->string('certificate_link')->nullable();
            $table->string('other_link')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('applications');
    }
};
