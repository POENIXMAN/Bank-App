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
            $table->unsignedBigInteger('clientId');
            $table->unsignedBigInteger('accountId');
            $table->enum('status', ['pending', 'approved', 'disapproved'])->default('pending');;
            $table->timestamps();

            $table->foreign('clientId')->references('id')->on('users');
            $table->foreign('accountId')->references('id')->on('accounts');
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_creation_requests');
    }
};

