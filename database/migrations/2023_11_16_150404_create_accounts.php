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
            $table->string('clientName');
            $table->bigInteger('amount');
            $table->enum('currency', ['LBP', 'USD', 'EUR']); // Enum column for currency
            $table->enum('status', ['pending', 'approved', 'disapproved'])->default('pending');
            $table->boolean('is_enabled')->default(true);
            $table->unsignedBigInteger('clientId');
            $table->timestamps();

            // Foreign key relationship with the users table
            $table->foreign('clientId')->references('id')->on('users');
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
