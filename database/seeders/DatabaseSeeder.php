<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Disliked;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user dummy
        $user = User::insert([
            [
                'name' => 'User Dummy',
                'email' => 'dummy@example.com',
                'password' => Hash::make('password'), // password: 'password'
            ],
            [
                'name' => 'Randi',
                'email' => 'randi@gmail.com',
                'password' => Hash::make('rahasia123'), // password: 'password'
            ],
        ]);

        // Panggil TodoSeeder dan kirim user
        $this->callWith(\Database\Seeders\TodoSeeder::class, [
            'user' => $user
        ]);
        $this->call([
            LikesSeeder::class,
            DislikesSeeder::class,
            CommentsSeeder::class
        ]);
    }
}
