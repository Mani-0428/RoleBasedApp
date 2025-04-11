<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\TeacherController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🏠 Home page
Route::get('/', function () {
    return view('welcome');
});

// 📊 Dashboard (requires auth + email verification)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔐 Auth routes (login, register, forgot password, etc.)
require __DIR__ . '/auth.php';

// 👤 Profile management (requires login)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 📚 Marks management (requires login)
Route::middleware(['auth'])->group(function () {
    Route::get('/marks', [MarkController::class, 'index'])->name('marks.index');
    Route::get('/marks/create', [MarkController::class, 'create'])->name('marks.create');
    Route::post('/marks', [MarkController::class, 'store'])->name('marks.store');
});

// 👨‍🏫 Teacher-specific routes (requires login & email verification)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/teacher/select-class', [TeacherController::class, 'selectClass'])->name('teacher.select-class');
    Route::post('/teacher/view-marks', [TeacherController::class, 'viewMarks'])->name('teacher.view-marks');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/teacher/select-class', [TeacherController::class, 'selectClass'])->name('teacher.select-class');
    Route::post('/teacher/view-marks', [TeacherController::class, 'viewMarks'])->name('teacher.view-marks');
    Route::post('/teacher/export-marks', [TeacherController::class, 'exportMarks'])->name('teacher.export-marks');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/marks', [MarkController::class, 'index'])->name('marks.index');
    Route::post('/marks', [MarkController::class, 'store'])->name('marks.store');
});


use Illuminate\Support\Facades\Log;

Route::get('/log-sql', function () {
    // Only for debugging – remove in production!
    $logFile = storage_path('logs/laravel.log');
    $lines = file($logFile);
    $queries = [];

    foreach ($lines as $line) {
        if (str_contains($line, 'SQL:')) {
            $queries[] = [
                'sql' => trim(str_replace('local.INFO: SQL: ', '', $line)),
                'bindings' => '',
                'time' => ''
            ];
        }

        if (str_contains($line, 'Bindings:')) {
            $queries[count($queries) - 1]['bindings'] = json_decode(trim(str_replace('local.INFO: Bindings: ', '', $line)), true);
        }

        if (str_contains($line, 'Time:')) {
            $queries[count($queries) - 1]['time'] = trim(str_replace('local.INFO: Time: ', '', $line));
        }
    }

    return response()->json($queries);
});
