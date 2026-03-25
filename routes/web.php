<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// REPORT ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    // Allow both Admin and Officer to update
    Route::patch('/admin/reports/{report}', [ReportController::class, 'update'])->name('admin.reports.update'); 
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::get('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
    
    // User Management
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    // Evaluation
    Route::get('/evaluations', [\App\Http\Controllers\EvaluationController::class, 'index'])->name('evaluations.index');
});

// SUBSCRIPTION ROUTES
Route::post('/subscription/toggle', [\App\Http\Controllers\SubscriptionController::class, 'toggle'])->middleware('auth')->name('subscription.toggle');

// NOTIFICATION ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// USER DASHBOARD
Route::get('/dashboard', [ReportController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/my-reports', [ReportController::class, 'citizenReports'])->middleware('auth')->name('reports.citizen');

// ADMIN & OFFICER DASHBOARD
Route::get('/admin/dashboard', [ReportController::class, 'index'])
    ->middleware(['auth'])
    ->name('admin.dashboard');

Route::get('/admin/manage-reports', [ReportController::class, 'adminReports'])
    ->middleware(['auth'])
    ->name('admin.reports.manage');

// PROFILE ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

