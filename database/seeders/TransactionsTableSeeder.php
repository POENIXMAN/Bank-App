<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed transactions table
        DB::table('transactions')->insert([
            'from_account_id' => 1, // Reference to the account with id 1
            'to_account_id' => 2, // Reference to a different account, e.g., account with id 2
            'amount' => 1000,
            'currency' => 'USD',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // You can add more seed data as needed
        DB::table('transactions')->insert([
            'from_account_id' => 2, // Reference to the account with id 2
            'to_account_id' => 1, // Reference to a different account, e.g., account with id 1
            'amount' => 500,
            'currency' => 'EUR',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
