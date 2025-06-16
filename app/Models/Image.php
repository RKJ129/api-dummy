<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = ['todo_id', 'image'];

    public function todo() 
    {
        $this->belongsTo(Todo::class, 'todo_id', 'id');
    }
}
