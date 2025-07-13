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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fio');
            $table->string('phone');
            $table->string('avatar')->default('/avatars/avatar.png');
            $table->string('mail')->nullable();
            $table->string('password');
            $table->string('iin');
            $table->string('real_password');
            $table->string('job_title');
            $table->foreignId('audan_id')->on('audans')->cascadeOnDelete();
            $table->foreignId('role_id')->on('roles')->cascadeOnDelete();
            $table->string('autobiography_link')->nullable();
            $table->string('certificate_link')->nullable();
            $table->string('document_link')->nullable();
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
        Schema::dropIfExists('users');
    }
};
