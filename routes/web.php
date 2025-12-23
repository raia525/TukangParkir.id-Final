<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\SystemTimeController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\AdminController;

Route::middleware(['auth', IsAdmin::class])->group(function() {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/reservasi', [AdminController::class, 'reservasi'])->name('admin.reservasi');
    Route::get('/admin/history', [AdminController::class, 'history'])->name('admin.history');
});

Route::get('/', function () {
    return view('home');
})->middleware('auth');


Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi')->middleware('auth');
Route::get('/reservasi/json', [ReservasiController::class, 'jsonStatus'])->name('reservasi.json')->middleware('auth');
Route::get('/sync-firebase', [ReservasiController::class, 'syncFirebaseStatus'])
     ->name('sync.firebase')->middleware('auth');

Route::post('/reservasi/submit', [ReservasiController::class, 'submit'])
    ->name('reservasi.submit');

Route::get('/reservasi/cancel', [ReservasiController::class, 'cancel'])
    ->name('reservasi.cancel')->middleware('auth');

Route::get('/reservasi/status', [ReservasiController::class, 'syncFirebaseStatus'])
    ->name('reservasi.status')
    ->middleware('auth');

Route::post('/reservasi/finish', [ReservasiController::class, 'finish'])
    ->name('reservasi.finish');

Route::post('/reservasi/exit', [ReservasiController::class, 'exitParking'])
    ->name('reservasi.exit');

Route::post('/reservasi/pay', [ReservasiController::class, 'pay'])
    ->name('reservasi.pay');

Route::get('/reservasi/history', [ReservasiController::class, 'history'])
    ->name('reservasi.history')->middleware('auth');

Route::post('/system-time/set', [SystemTimeController::class, 'set'])->name('system.time.set');
Route::post('/system-time/reset', [SystemTimeController::class, 'reset'])->name('system.time.reset');

Route::get('/scanner', function () {
    return view('scanner');
})->middleware('auth');