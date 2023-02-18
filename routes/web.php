<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

// Require authentication for all routes
Route::group([
    'middleware' => 'auth',
], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Degree Programs
    Route::group([
        'prefix' => 'degree-programs',
        'as' => 'degreePrograms.',
    ], function(){
        Route::get('/', [App\Http\Controllers\DegreeProgramController::class, 'index'])->name('index');
        Route::post('/import', [App\Http\Controllers\DegreeProgramController::class, 'import'])->name('import');
    });

    // Students
    Route::group([
        'prefix' => 'students',
        'as' => 'students.',
    ], function(){
        Route::get('/', [App\Http\Controllers\StudentController::class, 'index'])->name('index');
        Route::post('/import', [App\Http\Controllers\StudentController::class, 'import'])->name('import');
    });
});

