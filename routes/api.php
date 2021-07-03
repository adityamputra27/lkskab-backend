<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\BoardListController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\BoardMemberController;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function (){
    Route::prefix('auth')->group(function () {
        Route::get('user', [AuthController::class, 'getUser']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::get('logout', [AuthController::class, 'logout'])->middleware('authAPI');
    });

    Route::middleware(['authAPI'])->group(function () {
        Route::resource('board', BoardController::class);
        Route::resource('board/{board}/list', BoardListController::class);
        Route::resource('board/{board}/list/{list}/card', CardController::class);
        Route::resource('board/{board}/member', BoardMemberController::class);
        
        Route::post('board/{board}/list/{list}/right', [BoardListController::class, 'moveRight']);
        Route::post('board/{board}/list/{list}/left', [BoardListController::class, 'moveLeft']);
        Route::post('card/{card}/up', [CardController::class, 'moveUp']);
        Route::post('card/{card}/down', [CardController::class, 'moveDown']);

        Route::post('card/{card}/move/{list}', [CardController::class, 'moveToAnotherList']);
    });
});
// Route::resource('/test', TestController::class);

