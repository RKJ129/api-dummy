<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;

    protected $table = 'todo';
    protected $fillable = ['title', 'description', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'todo_id', 'id');
    }

    public function likeds()
    {
        return $this->hasMany(Liked::class, 'todo_id', 'id');
    }
    public function dislikeds()
    {
        return $this->hasMany(Disliked::class, 'todo_id', 'id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'todo_id', 'id');
    }

}
