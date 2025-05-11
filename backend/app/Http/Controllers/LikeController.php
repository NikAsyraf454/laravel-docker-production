<?php

namespace App\Http\Controllers;

use App\Events\LikeCountUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    private static $currentLikes = 0; // Simple in-memory counter for demo

    public function like()
    {
        // In a real application, you would update your database here
        self::$currentLikes++;

        // Dispatch the event
        event(new LikeCountUpdated(self::$currentLikes));
        Log::info("Like once");

        return response()->json(['status' => 'success', 'likes' => self::$currentLikes]);
    }
}
