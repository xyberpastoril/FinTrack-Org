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

// Redirect '/' to '/login'
Route::get('/', function () {
    return redirect('/login');
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

    Route::group([
        'middleware' => ['semester'],
    ], function(){
        // Users
        Route::group([
            'prefix' => 'users',
            'as' => 'users.',
            'middleware' => ['admin'],
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
            'middleware' => ['admin'],
        ], function(){
            Route::get('/', [App\Http\Controllers\DegreeProgramController::class, 'index'])->name('index');
            Route::post('/import', [App\Http\Controllers\DegreeProgramController::class, 'import'])->name('import');
        });

        // Students
        Route::group([
            'prefix' => 'students',
            'as' => 'students.',
            'middleware' => ['admin'],
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
        ], function(){
            Route::get('/', [App\Http\Controllers\EventController::class, 'index'])->name('index')->middleware('admin');
            Route::post('/', [App\Http\Controllers\EventController::class, 'store'])->name('store')->middleware('admin');
            Route::get('/{event}', [App\Http\Controllers\EventController::class, 'show'])->name('show')->middleware('admin');
            Route::get('/{event}/edit', [App\Http\Controllers\EventController::class, 'edit'])->name('edit')->middleware('admin');
            Route::put('/{event}', [App\Http\Controllers\EventController::class, 'update'])->name('update')->middleware('admin');
            Route::delete('/{event}', [App\Http\Controllers\EventController::class, 'destroy'])->name('destroy')->middleware('admin');

            Route::group([
                'prefix' => '{event}/attendance',
                'as' => 'attendances.',
            ], function(){
                Route::post('/', [App\Http\Controllers\AttendanceEventController::class, 'store'])->name('store')->middleware('admin');
                Route::get('/{attendance}', [App\Http\Controllers\AttendanceEventController::class, 'show'])->name('show')->middleware('admin');
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

        // Attendance Events
        Route::group([
            'prefix' => 'attendances',
            'as' => 'attendances.',
        ], function(){
            Route::get('/active', [App\Http\Controllers\AttendanceEventController::class, 'index'])->name('index');
        });

        // Fees
        Route::group([
            'prefix' => 'fees',
            'as' => 'fees.',
            'middleware' => ['admin'],
        ], function(){
            Route::get('/', [App\Http\Controllers\FeeController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\FeeController::class, 'store'])->name('store');
            // Route::get('/{fee}', [App\Http\Controllers\FeeController::class, 'show'])->name('show');
            Route::get('/{fee}/edit', [App\Http\Controllers\FeeController::class, 'edit'])->name('edit');
            Route::put('/{fee}', [App\Http\Controllers\FeeController::class, 'update'])->name('update');
            Route::delete('/{fee}', [App\Http\Controllers\FeeController::class, 'destroy'])->name('destroy');
        });

        // Fees
        Route::group([
            'prefix' => 'items',
            'as' => 'items.',
            'middleware' => ['admin'],
        ], function(){
            Route::get('/', [App\Http\Controllers\ItemController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\ItemController::class, 'store'])->name('store');
            // Route::get('/{item}', [App\Http\Controllers\ItemController::class, 'show'])->name('show');
            Route::get('/{item}/edit', [App\Http\Controllers\ItemController::class, 'edit'])->name('edit');
            Route::put('/{item}', [App\Http\Controllers\ItemController::class, 'update'])->name('update');
            Route::delete('/{item}', [App\Http\Controllers\ItemController::class, 'destroy'])->name('destroy');
        });

        // Payment
        Route::group([
            'prefix' => 'payment',
            'as' => 'payment.',
            'middleware' => ['admin'],
        ], function(){
            Route::get('/', [App\Http\Controllers\PaymentController::class, 'index'])->name('index');
        });

        // Receipt
        Route::group([
            'prefix' => 'receipts',
            'as' => 'receipts.',
            'middleware' => ['admin'],
        ], function(){
            Route::get('/{receipt}/pdf', [App\Http\Controllers\ReceiptController::class, 'pdf'])->name('pdf');
        });

        // AJAX
        Route::group([
            'prefix' => 'ajax',
            'as' => 'ajax.',
        ], function(){

            Route::group([
                'prefix' => 'payments',
                'as' => 'payments.',
                'middleware' => ['admin'],
            ], function(){
                Route::post('/', [App\Http\Controllers\PaymentController::class, 'storeAjax'])->name('store');
            });

            Route::group([
                'prefix' => 'students',
                'as' => 'students.',
            ], function(){

                Route::group([
                    'prefix' => 'enrolled',
                    'as' => 'enrolled.',
                    'middleware' => ['admin'],
                ], function(){
                    Route::get('/search/{query?}', [App\Http\Controllers\EnrolledStudentController::class, 'searchAjax'])->name('search');
                    Route::get('/{enrollee}/fees', [App\Http\Controllers\EnrolledStudentController::class, 'getFeesAjax'])->name('getFees');
                    Route::get('/{enrollee}/fines', [App\Http\Controllers\EnrolledStudentController::class, 'getFinesAjax'])->name('getFines');
                });
            });

            Route::group([
                'prefix' => 'items',
                'as' => 'items.',
                'middleware' => ['admin'],
            ], function() {
                Route::get('/search/{query?}', [App\Http\Controllers\ItemController::class, 'searchAjax'])->name('search');
            });

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
});
