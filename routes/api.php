<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);
Route::post("logout", [AuthController::class, "logout"])->middleware('auth:sanctum');

//Podcasts
Route::get("podcasts",[PodcastController::class,"index"]);
Route::get("podcasts/{id}/details",[PodcastController::class,"show"]);
Route::get('podcasts/search', [PodcastController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
Route::post("podcasts", [PodcastController::class, "store"]);
Route::put('podcasts/{id}/update', [PodcastController::class, 'update']);
Route::delete('podcasts/{id}', [PodcastController::class, 'destroy']);
});



//episode
Route::get("podcasts/{podcast_id}/episodes",[EpisodeController::class,"index"]);
Route::get("episodes/{id}/details",[EpisodeController::class,"show"]);
Route::get('episodes/search', [EpisodeController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
Route::post("podcasts/{podcast_id}/episodes", [EpisodeController::class, "store"]);
Route::put('episodes/{id}/update', [EpisodeController::class, 'update']);
Route::delete('episodes/{id}', [EpisodeController::class, 'destroy']);
});



// Animateurs
Route::get("hosts",[UserController::class,"index"]);
Route::get("hosts/{id}/details",[UserController::class,"show"]);

Route::middleware('auth:sanctum')->group(function () {
Route::post("hosts", [UserController::class, "store"]);
Route::put("hosts/{id}/update", [UserController::class, "update"]);
Route::delete('hosts/{id}', [UserController::class, 'destroy']);
Route::get('users', [UserController::class, 'allUsers']);

});