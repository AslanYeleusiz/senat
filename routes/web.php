<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProjectController;
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

Route::get('/', function () {
   return Inertia::render('home');
})->name('index');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('adminLoginShow');
    Route::post('/login', [AdminAuthController::class, 'adminLoginForm'])->name('adminLoginForm');
});
Route::get('/project/{id}/createPasport', [ProjectController::class, 'createPasport'])->name('createPasport');
Route::post('/set-locale', function (Illuminate\Http\Request $request) {
    $locale = $request->input('locale');
    session(['locale' => $locale]);
    app()->setLocale($locale);
    return response()->json(['success' => true]);
});
Route::middleware('adminAuth')->group(function () {
    Route::get('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});
