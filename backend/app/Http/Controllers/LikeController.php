<?php

namespace App\Http\Controllers;

use App\Events\LikeCountUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\LikeCount;

class LikeController extends Controller
{
    // private static $currentLikes = 0; // Simple in-memory counter for demo

    public function like()
    {
        $LikeCount = LikeCount::where('id', 1)->first();
        // In a real application, you would update your database here
        Log::info($LikeCount);
        $LikeCount->likeCount++;
        $LikeCount->update(['likeCount'=>$LikeCount->likeCount]);
        // Dispatch the event
        broadcast(new LikeCountUpdated($LikeCount->likeCount));

        return response()->json(['status' => 'success', 'likes' => $LikeCount->likeCount]);
    }
}
