<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\RoutePath;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // \App\Jobs\ProcessProductScan::dispatch();
    return view('welcome');
});


$limiter = config('fortify.limiters.login');
Route::post(RoutePath::for('login', '/login'), [AuthenticatedSessionController::class, 'store'])
    ->middleware(array_filter([
        'guest:'.config('fortify.guard'),
        $limiter ? 'throttle:'.$limiter : null,
    ]))
    ->name('login');

Route::post(RoutePath::for('register', '/register'), [RegisteredUserController::class, 'store'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('register');

Route::post(RoutePath::for('password.email', '/forgot-password'), [PasswordResetLinkController::class, 'store'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('password.email');

Route::get(RoutePath::for('password.reset', '/reset-password/{token}'), [NewPasswordController::class, 'create'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('password.reset');

Route::post(RoutePath::for('password.update', '/reset-password'), [NewPasswordController::class, 'store'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('password.update');
