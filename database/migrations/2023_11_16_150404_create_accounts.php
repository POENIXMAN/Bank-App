<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('accountNum')->unique();
            $table->string('ClientName');
            $table->decimal('ammount', 10, 2); // A decimal amount field with 10 total digits and 2 decimal places
            $table->enum('currency', ['LBP', 'USD', 'EUR']); // Enum column for currency
            $table->unsignedBigInteger('ClientId');
            $table->timestamps();

            // Foreign key relationship with the users table
            $table->foreign('ClientId')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
