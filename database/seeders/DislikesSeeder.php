<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DislikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dislikes = [];
        $jumlah = 50;
        $now = Carbon::now();
        for($i = 0; $i <= $jumlah; $i++) {
            $dislike = [
                'todo_id' => rand(1, 20),
                'user_id' => rand(1, 2),
                'created_at' => $now,
                'updated_at' => $now,
            ];
            array_push($dislikes, $dislike);
        }

        DB::table('disliked')->insert($dislikes);
    }
}
