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
    Route::post('/home', [App\Http\Controllers\HomeController::class, 'setSemester'])->name('home.setSemester');

    // Account
    Route::group([
        'prefix' => 'account',
        'as' => 'account.',
    ], function(){
        Route::get('/', [App\Http\Controllers\AccountController::class, 'index'])->name('index');

        Route::post('/update/password', [App\Http\Controllers\AccountController::class, 'updatePassword'])->name('updatePassword');
    });

    // Users
    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
        'middleware' => ['admin', 'semester'],
    ], function(){
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\UserController::class, 'store'])->name('store');
        Route::get('/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    });

    // Degree Programs
    Route::group([
        'prefix' => 'degree-programs',
        'as' => 'degreePrograms.',
        'middleware' => ['admin', 'semester'],
    ], function(){
        Route::get('/', [App\Http\Controllers\DegreeProgramController::class, 'index'])->name('index');
        Route::post('/import', [App\Http\Controllers\DegreeProgramController::class, 'import'])->name('import');
    });

    // Students
    Route::group([
        'prefix' => 'students',
        'as' => 'students.',
        'middleware' => ['admin', 'semester'],
    ], function(){

        Route::group([
            'prefix' => 'enrolled',
            'as' => 'enrolled.',
        ], function(){
            Route::get('/', [App\Http\Controllers\EnrolledStudentController::class, 'index'])->name('index');
            Route::post('/import', [App\Http\Controllers\EnrolledStudentController::class, 'import'])->name('import');
        });
    });

    // Events
    Route::group([
        'prefix' => 'events',
        'as' => 'events.',
        'middleware' => ['semester'],
    ], function(){
        Route::get('/', [App\Http\Controllers\EventController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\EventController::class, 'store'])->name('store')->middleware('admin');
        Route::get('/{event}', [App\Http\Controllers\EventController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [App\Http\Controllers\EventController::class, 'edit'])->name('edit')->middleware('admin');
        Route::put('/{event}', [App\Http\Controllers\EventController::class, 'update'])->name('update')->middleware('admin');
        Route::delete('/{event}', [App\Http\Controllers\EventController::class, 'destroy'])->name('destroy')->middleware('admin');

        Route::group([
            'prefix' => '{event}/attendance',
            'as' => 'attendances.',
        ], function(){
            Route::post('/', [App\Http\Controllers\AttendanceEventController::class, 'store'])->name('store')->middleware('admin');
            Route::get('/{attendance}', [App\Http\Controllers\AttendanceEventController::class, 'show'])->name('show');
            Route::get('/{attendance}/scan', [App\Http\Controllers\AttendanceEventController::class, 'scan'])->name('scan');
            Route::get('/{attendance}/edit', [App\Http\Controllers\AttendanceEventController::class, 'edit'])->name('edit')->middleware('admin');
            Route::put('/{attendance}', [App\Http\Controllers\AttendanceEventController::class, 'update'])->name('update')->middleware('admin');
            Route::delete('/{attendance}', [App\Http\Controllers\AttendanceEventController::class, 'destroy'])->name('destroy')->middleware('admin');

            Route::group([
                'as' => 'logs.',
                'middleware' => 'admin',
            ], function(){
                Route::post('/{attendance}/logs/export/', [App\Http\Controllers\AttendanceEventLogController::class, 'export'])->name('export');
            });
        });

    });

    // AJAX
    Route::group([
        'prefix' => 'ajax',
        'as' => 'ajax.',
        'middleware' => ['admin', 'semester'],
    ], function(){

        Route::group([
            'prefix' => 'events/{event}',
            'as' => 'event.',
        ], function(){
            Route::group([
                'prefix' => 'attendances/{attendance}',
                'as' => 'attendances.',
            ], function(){
                Route::post('/logs/store/', [App\Http\Controllers\AttendanceEventLogController::class, 'storeAjax'])->name('storeAjax');
                Route::post('/logs/store/byStudentId/', [App\Http\Controllers\AttendanceEventLogController::class, 'storeByStudentIdAjax'])->name('storeByStudentIdAjax');
                Route::get('/students/search/{query?}', [App\Http\Controllers\AttendanceEventLogController::class, 'searchStudentAjax'])->name('searchStudentAjax');
                Route::get('/students/count',[App\Http\Controllers\AttendanceEventLogController::class, 'refreshCountAjax'])->name('refreshCountAjax');
            });
        });
    });
});
