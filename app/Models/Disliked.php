<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disliked extends Model
{
    protected $table = 'disliked';
    protected $fillable = ['user_id', 'todo_id'];
}
