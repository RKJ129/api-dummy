<?php

namespace Database\Seeders;

use App\Models\Todo;
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
         $statuses = ['aktif', 'tidak aktif'];

        for ($i = 0; $i < 200; $i++) {
            Todo::create([
                'title' => 'Todo ' . Str::random(10),
                'description' => 'Deskripsi ' . Str::random(30),
                'status' => $statuses[array_rand($statuses)],
                'image' => 'default.jpeg',
            ]);
        }
    }
}
