<?php

use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::resource('users', UsersController::class)->except('show');

    Route::get('{date?}', [DashboardController::class, 'index'])
            ->where('date', '\d{4}-\d{2}-\d{2}')
            ->name('dashboard');
});

require __DIR__.'/auth.php';
