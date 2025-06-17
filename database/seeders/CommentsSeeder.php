<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments = [];
        $jumlah = 50;
        $now = Carbon::now();
        for($i = 0; $i <= $jumlah; $i++) {
            $comment = [
                'comment' => Str::random(20),
                'todo_id' => rand(1, 20),
                'user_id' => rand(1, 2),
                'created_at' => $now,
                'updated_at' => $now,
            ];
            array_push($comments, $comment);
        }

        DB::table('comments')->insert($comments);
    }
}
