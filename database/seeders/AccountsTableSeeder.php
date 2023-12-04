<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed accounts table
        DB::table('accounts')->insert([
            'accountNum' => 'AC001',
            'clientName' => 'Client X',
            'amount' => 50000,
            'currency' => 'USD',
            'status' => 'approved',
            'is_enabled' => true,
            'clientId' => 1, // Reference to the user with id 1
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // You can add more seed data as needed
        DB::table('accounts')->insert([
            'accountNum' => 'AC002',
            'clientName' => 'Client Y',
            'amount' => 75000,
            'currency' => 'EUR',
            'status' => 'pending',
            'is_enabled' => true,
            'clientId' => 2, // Reference to the user with id 2
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
