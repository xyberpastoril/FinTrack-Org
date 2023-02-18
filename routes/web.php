<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

    // Events
    Route::group([
        'prefix' => 'events',
        'as' => 'events.',
    ], function(){
        Route::get('/', [App\Http\Controllers\EventController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\EventController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\EventController::class, 'store'])->name('store');
        Route::get('/{event}', [App\Http\Controllers\EventController::class, 'show'])->name('show');
        Route::get('/{event}/scan', [App\Http\Controllers\EventController::class, 'scan'])->name('scan');
        Route::get('/{event}/edit', [App\Http\Controllers\EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [App\Http\Controllers\EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [App\Http\Controllers\EventController::class, 'destroy'])->name('destroy');
    });

    // AJAX
    Route::group([
        'prefix' => 'ajax',
        'as' => 'ajax.',
    ], function(){

        Route::group([
            'prefix' => 'events',
            'as' => 'events.',
        ], function(){

            Route::post('{event}/logs/store/', [App\Http\Controllers\EventLogController::class, 'storeAjax'])->name('storeAjax');
            Route::post('{event}/logs/store/byStudentId/', [App\Http\Controllers\EventLogController::class, 'storeByStudentIdAjax'])->name('storeByStudentIdAjax');
            Route::get('{event}/students/search/{query?}', [App\Http\Controllers\EventLogController::class, 'searchStudentAjax'])->name('searchStudentAjax');
        });

    });

});
