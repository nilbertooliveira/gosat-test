<?php

namespace Database\Seeders;

use App\Infrastructure\Database\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            [
                "email" => "administrator@test.com.br",
            ],
            [
                "name" => 'Administrator',
                "email" => "administrator@test.com.br",
                "password" => Hash::make('123456')
            ]);

        User::firstOrCreate(
            [
                "email" => "reader@test.com.br",
            ],
            [
                "name" => 'Reader',
                "email" => "reader@test.com.br",
                "password" => Hash::make('123456')
            ]);

        User::firstOrCreate(
            [
                "email" => "writer@test.com.br",
            ],
            [
                "name" => 'Writer',
                "email" => "writer@test.com.br",
                "password" => Hash::make('123456')
            ]);
    }
}
