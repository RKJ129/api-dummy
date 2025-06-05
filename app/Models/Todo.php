<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $table = 'todo';
    protected $fillable = ['title', 'description', 'status', 'image'];

    //accessor full url
    protected $appends =['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }
}
