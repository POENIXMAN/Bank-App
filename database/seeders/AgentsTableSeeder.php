<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AgentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed one or more agents
        $agents = [
            [
                'name' => 'Agent 1',
                'email' => 'agent1@example.com',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Agent 2',
                'email' => 'agent2@example.com',
                'password' => Hash::make('password456'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more agents as needed
        ];

        // Insert the agents into the 'agents' table
        DB::table('agents')->insert($agents);
    }
}
