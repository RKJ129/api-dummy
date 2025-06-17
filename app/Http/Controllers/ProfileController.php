<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $userId = auth('api')->id();
        $profile = User::with('todos')
            ->withCount([
                'todos', 
                'likedTodos as likes_count'
            ])
            ->findOrFail($userId);

        return response()->json([
            'user_id' => $userId,
            'profile' => $profile
        ]);
    }
}
