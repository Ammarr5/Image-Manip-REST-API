<?php

use App\Http\Controllers\ImageManipulationController;
use App\Http\Controllers\UserController;
use App\Models\ImageManipulation;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::prefix("v1")->group(function () {
        Route::post("/image/resize", [ImageManipulationController::class, "resize"]);
        Route::post("/image/pixelate", [ImageManipulationController::class, "pixelate"]);
        Route::post("/image/greyscale", [ImageManipulationController::class, "greyscale"]);
        Route::post("/image/compress", [ImageManipulationController::class, "compress"]);
        Route::apiResource("image", ImageManipulationController::class);
        Route::get("/user/logout", [UserController::class, "logout"]);
    });
});

Route::prefix("v1")->group(function () {
    Route::post("/user/register", [UserController::class, "create"]);
    Route::post("/user/login", [UserController::class, "login"]);
});
