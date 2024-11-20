<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // dashboard tab
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // job applications tab
    Route::get('/jobs', function () {
        return Inertia::render('Jobs/Index');
    })->name('jobs.index');

    Route::get('/jobs/create', function () {
        return Inertia::render('Jobs/Create');
    })->name('jobs.create');

    Route::get('/jobs/{job}/edit', function () {
        return Inertia::render('Jobs/Edit');
    })->name('jobs.edit');

    // analytics tab
    Route::get('/analytics', function () {
        return Inertia::render('Analytics/Index');
    })->name('analytics');

    // --controller--
    Route::resource('jobs', JobApplicationController::class);

    Route::post('/companies', [CompanyController::class, 'store'])
        ->name('companies.store')
        ->middleware(['auth', 'verified']);

    Route::get('/analytics', [AnalyticsController::class,'index'])
        ->name('analytics')
        ->middleware(['auth', 'verified']);

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
