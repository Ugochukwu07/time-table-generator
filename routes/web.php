<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SchedulerController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\SessionController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PageController::class, 'home'])->name('home');

Route::controller(UploadController::class)->prefix('upload')->name('upload.')->group(function(){
    Route::get('/first', 'uploadFirst')->name('first');
    Route::post('/dropzone', 'uploadDropzone')->name('dropzone');

    Route::prefix('stats')->name('stats.')->group(function(){
        Route::get('/departments', 'statsDepartments')->name('departments');
        Route::get('/courses', 'statsCourses')->name('courses');
        Route::get('/course/{id}', 'statsCourse')->name('course');
        Route::get('/department/{id}', 'statsDepartment')->name('department');
    });
});

Route::controller(SchedulerController::class)->prefix('scheduler')->name('scheduler.')->group(function(){
    Route::get('/test', 'preview')->name('preview');
    Route::get('/preview', 'preview')->name('preview');

    Route::get('/print/settings', 'printSetup')->name('print.setup');
    Route::post('/setup', 'setUpSave')->name('setup.save');

    Route::get('/generated/time-table', 'generatedTimeTable');
});

Route::resource('sessions', SessionController::class);
