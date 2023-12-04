<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        // Seed specific tables
        $this->call(UsersTableSeeder::class);
        $this->call(AgentsTableSeeder::class);
        $this->call(AccountsTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
    }
}
