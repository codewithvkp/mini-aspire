<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed admin user

        User::query()->create([
            'name' => 'Aspire Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'admin',
        ]);

        // Seed normal user

        User::query()->create([
            'name' => 'Aspire User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'user',
        ]);
    }
}
