<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AudanController;
use App\Http\Controllers\Admin\ForumController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectTypeController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\HomeController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/',[HomeController::class, 'index'])->name('index');
Route::get('/dashboard',[HomeController::class, 'index'])->name('home.index');
Route::resource('forums', ForumController::class)->names('forums');
Route::post('forums/{id}/comments', [ForumController::class, 'loadComments']);
Route::post('forums/{id}/commentstore', [ForumController::class, 'commentstore'])->name('forums.comment');
Route::put('forums/{id}/toggleStatus', [ForumController::class, 'toggleStatus'])->name('forums.toggleStatus');
Route::resource('notifications', NotificationController::class)->names('notifications');
Route::get('/get/ntfs-count', [NotificationController::class, 'ntfs_count'])->name('ntfs_count');
Route::post('/notifications/{id}/read', [NotificationController::class, 'ntfs_read'])->name('ntfs_read');




Route::resource('roles', RoleController::class)->except(['show'])->names('roles')->middleware('role:1,2');
Route::resource('audans', AudanController::class)->except(['show'])->names('audans')->middleware('role:1,2,4,5');
Route::resource('projectTypes', ProjectTypeController::class)->names('projectTypes')->middleware('role:1,2');
Route::resource('users', UserController::class)->except(['show'])->names('users')->middleware('role:1,2,5');
Route::resource('projects', ProjectController::class)->names('projects')->middleware('role:1,2,3,4');
Route::resource('applications', ApplicationController::class)->except(['show'])->names('applications')->middleware('role:1,7');





Route::get('users/search', [UserController::class, 'userSearch'])->name('userSearch');
Route::post('project/{id}/stagestore', [ProjectController::class, 'stagestore'])->name('projects.stagestore');
Route::post('stage/{id}/confirm', [ProjectController::class, 'stageConfirm'])->name('stageConfirm');
Route::post('applicationStore', [ProjectController::class, 'applicationStore'])->name('applicationStore');

Route::resource('questions', QuestionController::class)->except(['index', 'create', 'edit', 'show'])->names('questions');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile/uploadautobiography', [ProfileController::class, 'uploadautobiography'])->name('profile.uploadautobiography');
Route::delete('/profile/deleteautobiography', [ProfileController::class, 'deleteautobiography'])->name('profile.deleteautobiography');
Route::post('/profile/uploadcertificate', [ProfileController::class, 'uploadcertificate'])->name('profile.uploadcertificate');
Route::delete('/profile/deletecertificate', [ProfileController::class, 'deletecertificate'])->name('profile.deletecertificate');
Route::post('/profile/uploaddocument', [ProfileController::class, 'uploaddocument'])->name('profile.uploaddocument');
Route::post('/profile/uploadavatar', [ProfileController::class, 'uploadavatar'])->name('profile.uploadavatar');
Route::delete('/profile/deletedocument', [ProfileController::class, 'deletedocument'])->name('profile.deletedocument');

Route::post('/lang/{locale}', function ($locale) {
    session()->put('locale', $locale);
    return redirect()->back();
})->name('change.language');
//Route::middleware('adminAuth')->group(function () {
//    Route::resource('roles', RoleController::class)->except(['show'])->names('roles');
//});

