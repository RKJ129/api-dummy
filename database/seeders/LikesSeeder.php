<?php

namespace Database\Seeders;

use App\Models\Liked;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $likes = [];
        $jumlah = 50;
        $now = Carbon::now();
        for($i = 0; $i <= $jumlah; $i++) {
            $like = [
                'todo_id' => rand(1, 20),
                'user_id' => rand(1, 2),
                'created_at' => $now,
                'updated_at' => $now,
            ];
            array_push($likes, $like);
        }

        DB::table('liked')->insert($likes);
    }
}
