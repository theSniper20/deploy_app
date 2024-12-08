<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;
/*
use App\Http\Controllers\Auth\HospitalLoginController;
use App\Http\Controllers\Auth\HospitalRegisterController;
*/
//login and register routes
/*
Route::get('/login', [HospitalLoginController::class, 'showLoginForm'])->name('hospital.login');
Route::post('/login', [HospitalLoginController::class, 'login']);
Route::post('/logout', [HospitalLoginController::class, 'logout'])->name('hospital.logout');

Route::get('/register', [HospitalRegisterController::class, 'showRegistrationForm'])->name('hospital.register');
Route::post('/register', [HospitalRegisterController::class, 'register']);

*/

Route::get('/', function () {
    return view('welcome');
});

//Route::resource("/patient", PatientController::class)->middleware('auth');
Route::resource('departments', DepartmentController::class)->middleware('auth');
Route::get('/all-departments', [DepartmentController::class, 'getJsonDepartments'])->middleware('auth');
Route::resource('services', ServiceController::class)->middleware('auth');
Route::resource('devices', DeviceController::class)->middleware('auth');
Route::resource('scans', ScanController::class)->middleware('auth');

// categories CRUD
Route::post('/categ/{context}/config', [ScanController::class, 'categConfig'])->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
Route::get('test', [HospitalLoginController::class, 'showLoginForm']);
Route::get('test2', [HospitalRegisterController::class, 'register']);*/
require __DIR__.'/auth.php';
