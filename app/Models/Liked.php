<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liked extends Model
{
    protected $table = 'liked';
    protected $fillable = ['user_id', 'todo_id'];
}
