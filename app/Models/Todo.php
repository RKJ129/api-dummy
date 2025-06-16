<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;

    protected $table = 'todo';
    protected $fillable = ['title', 'description', 'status', 'image', 'user_id'];

    //konversi kolom image ke array
    protected  $casts = [
        'image' => 'array',
    ];

    //accessor full url
    protected $appends =['image_url'];

    public function getImageUrlAttribute($value)
    {
        // return $this->image ? asset($this->image) : null;
        return $value ? json_decode($value, true) : [];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
