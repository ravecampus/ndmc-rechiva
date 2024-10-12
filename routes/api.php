<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AdminDocumentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TypeOfPaperController;
use App\Http\Controllers\PublicDocumentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\DocumentAuthorController;


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
Route::get('/auth/out', [AuthController::class, 'signout']);

Route::middleware('auth:sanctum')->group( function () {
    
    Route::resource('/notification', NotificationController::class);
    Route::get('/faculty-notification', [NotificationController::class,'facultyNote']);
    Route::put('/faculty-notification/{id}', [NotificationController::class,'putfacultyNote']);
    
});
Route::resource('/setting-type-of-paper',TypeOfPaperController::class);
Route::get('/list-author',[AuthorController::class, 'listAuthor']);
Route::resource('/setting-author',AuthorController::class);
Route::resource('/setting-department',DepartmentController::class);
Route::resource('/document', DocumentController::class);
Route::resource('/admin-document', AdminDocumentController::class);
Route::post('/admin-disapprove', [AdminDocumentController::class, 'disapprove']);
Route::resource('/admin-feedback', FeedbackController::class);
Route::get('/admin-document-published', [AdminDocumentController::class, 'publishDoc']);
Route::get('/admin-document-archived', [AdminDocumentController::class, 'archiveDoc']);
Route::get('/admin-document-canceled', [AdminDocumentController::class, 'cancelDoc']);
Route::resource('/public-document', PublicDocumentController::class);
Route::get('/document-published', [DocumentController::class, 'publishedAuth']);
Route::get('/document-archived', [DocumentController::class, 'archivedAuth']);
Route::get('/document-canceled', [DocumentController::class, 'canceledAuth']);
Route::get('/list-topapers', [TypeOfPaperController::class,'listOfPaper']);
Route::get('/list-departments', [DepartmentController::class,'listDepartment']);


Route::post('/download', [StatisticController::class,'download']);
Route::post('/visitor', [StatisticController::class,'visitor']);
Route::get('/statistic', [StatisticController::class,'statistic']);
Route::get('/department-stat', [StatisticController::class,'departmentstat']);
Route::resource('/users', UserController::class);
Route::put('/users-password/{id}', [UserController::class,'changePassword']);
Route::put('/change-password/{id}', [UserController::class,'userChangePassword']);

Route::get('/users-faculty/{id}', [UserController::class,'getFaculty']);


Route::get('/recent-submission', [StatisticController::class,'recentSubmission']);

Route::resource('/admin-contact', AdminContactController::class);
Route::get('/faculty-recent', [DocumentController::class, 'recentfaculty']);
Route::get('/search-list', [DocumentController::class, 'searchList']);

Route::post('/upload-profile', [UserController::class, 'uploadProfile']);
Route::resource('/doc-author', DocumentAuthorController::class);
Route::delete('/doc-keyword/{id}', [DocumentAuthorController::class,'keyword']);
Route::post('/document-upload', [DocumentAuthorController::class,'docUpload']);
Route::delete('/document-to-archived/{id}', [DocumentAuthorController::class,'archived']);


