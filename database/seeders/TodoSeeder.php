<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $statuses = ['aktif', 'tidak aktif'];


        for ($i = 0; $i < 200; $i++) {
            Todo::create([
                'user_id' => $user->id,
                'title' => 'Todo ' . Str::random(10),
                'description' => 'Deskripsi ' . Str::random(30),
                'status' => $statuses[array_rand($statuses)],
                // 'image' => 'default.jpeg',
            ]);
        }
    }
}
