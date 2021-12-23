<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GamePlayerController;
use App\Http\Controllers\PlayersController;
use App\Http\Controllers\ServerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('server')->group(function(){
    Route::get('/auth', [GameController::class, "AuthPlayer"]);

    Route::prefix('ranking')->group(function(){
        Route::get('/players', [PlayersController::class, "getRankingPlayers"]);
        Route::get('/consortias', [PlayersController::class, "getRankingConsortias"]);
    });
    
    Route::prefix('game')->group(function(){
        Route::get('/serverstatus', [ServerController::class, "ServerStatus"]);
        Route::get('/createcharacter', [GamePlayerController::class, "createPlayerCharacter"]);
    });
});

Route::prefix('player')->group(function(){
    Route::get('characters', [GamePlayerController::class, "ApplicationGetUserCharacters"]);
    Route::get('character', [GamePlayerController::class, "ApplicationGetUserCharacter"]);
});