<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('account_creation_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->enum('status', ['pending', 'approved', 'disapproved'])->default('pending');;
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_creation_requests');
    }
};

