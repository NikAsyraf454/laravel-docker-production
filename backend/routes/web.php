<?php

use App\Jobs\LogCreatedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\LikeController;



Route::get('/', function () {
    return ['Laravel' => app()->version(), 'env' => Config::get('APP_ENV')];
});

Route::get('/', function () {
    return view('like');
});
Route::post('/like', [LikeController::class, 'like']);

Route::get('/upload', function () {
    return view('upload');
});

Route::get('/click', function() {
    dispatch(new \App\Jobs\ProcessClick());

    return response()->json([
        'click' => true
    ]);
});

Route::post('/upload', function (Request $request) {
    $request->validate([
        'file' => 'required|file|max:2048', // Max file size: 2MB
    ]);

    if ($request->file('file')->isValid()) {
        $path = $request->file('file')->store('uploads', 'public');

        dispatch(new LogCreatedUser());

        return back()->with('success', 'File uploaded successfully to: ' . $path);
    }

    return back()->withErrors('File upload failed.');
});

