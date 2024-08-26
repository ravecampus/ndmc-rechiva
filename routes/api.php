<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TypeOfPaperController;
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
Route::post('/auth', [AuthController::class, 'register']);
Route::post('/auth/in', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    

});
Route::resource('/setting-type-of-paper',TypeOfPaperController::class);
Route::resource('/setting-author',AuthorController::class);
Route::resource('/setting-category',CategoryController::class);
Route::resource('/document', DocumentController::class);