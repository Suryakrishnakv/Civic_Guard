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
    Route::get('/reports/detect-ward', [\App\Http\Controllers\WardController::class, 'detectWard'])->name('reports.detectWard');
    Route::get('/wards/detect', [\App\Http\Controllers\WardController::class, 'detectWard'])->name('wards.detect');
    Route::get('/reports/check-duplicate', [ReportController::class, 'checkDuplicate'])->name('reports.checkDuplicate');
    Route::get('/reports/map-data', [ReportController::class, 'mapData'])->name('reports.mapData');
    Route::post('/reports/{report}/support', [ReportController::class, 'support'])->name('reports.support');
    Route::post('/reports/{report}/comments', [ReportController::class, 'addComment'])->name('reports.comments.store');
    Route::post('/reports/{report}/rate', [ReportController::class, 'rateReport'])->name('reports.rate');

    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    // Allow both Admin and Officer to update
    Route::patch('/admin/reports/{report}', [ReportController::class, 'update'])->name('admin.reports.update'); 

    // Officer field work map planner & Batch actions
    Route::get('/admin/reports-map', [ReportController::class, 'officerMap'])->name('admin.reports.map');
    Route::post('/admin/reports/batch', [ReportController::class, 'batchUpdate'])->name('admin.reports.batch');

    // Moderation routes (also accessible to admins & officers of target department)
    Route::post('/admin/reports/{report}/merge', [ReportController::class, 'mergeReport'])->name('admin.reports.merge');
    Route::post('/admin/reports/{report}/spam', [ReportController::class, 'markSpam'])->name('admin.reports.spam');
    Route::post('/admin/reports/{report}/reassign', [ReportController::class, 'reassign'])->name('admin.reports.reassign');

    // Ward Management & Analytics (Admin & Officer)
    Route::get('/admin/wards', [\App\Http\Controllers\WardController::class, 'index'])->name('admin.wards.index');
    Route::get('/admin/wards/create', [\App\Http\Controllers\WardController::class, 'create'])->name('admin.wards.create');
    Route::post('/admin/wards', [\App\Http\Controllers\WardController::class, 'store'])->name('admin.wards.store');
    Route::get('/admin/wards/export/excel', [\App\Http\Controllers\WardController::class, 'exportExcel'])->name('admin.wards.export.excel');
    Route::get('/admin/wards/export/pdf', [\App\Http\Controllers\WardController::class, 'exportPdf'])->name('admin.wards.export.pdf');
    Route::get('/admin/wards/{ward}', [\App\Http\Controllers\WardController::class, 'show'])->name('admin.wards.show');
    Route::get('/admin/wards/{ward}/inspection-report', [\App\Http\Controllers\WardController::class, 'inspectionReport'])->name('admin.wards.inspectionReport');
    Route::get('/admin/wards/{ward}/edit', [\App\Http\Controllers\WardController::class, 'edit'])->name('admin.wards.edit');
    Route::put('/admin/wards/{ward}', [\App\Http\Controllers\WardController::class, 'update'])->name('admin.wards.update');
    Route::delete('/admin/wards/{ward}', [\App\Http\Controllers\WardController::class, 'destroy'])->name('admin.wards.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::get('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
    
    // User Management
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    // Evaluation
    Route::get('/evaluations', [\App\Http\Controllers\EvaluationController::class, 'index'])->name('evaluations.index');

    // Officer Management
    Route::get('/officers', [\App\Http\Controllers\OfficerController::class, 'index'])->name('officers.index');
    Route::get('/officers/create', [\App\Http\Controllers\OfficerController::class, 'create'])->name('officers.create');
    Route::post('/officers', [\App\Http\Controllers\OfficerController::class, 'store'])->name('officers.store');
    Route::delete('/officers/{user}', [\App\Http\Controllers\OfficerController::class, 'destroy'])->name('officers.destroy');

    // Department Management
    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);

    // Category SLA CRUD
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);

    // Advanced Municipal Analytics Dashboard
    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/data', [\App\Http\Controllers\AnalyticsController::class, 'ajaxData'])->name('analytics.data');
    Route::get('/analytics/export/excel', [\App\Http\Controllers\AnalyticsController::class, 'exportExcel'])->name('analytics.export.excel');
    Route::get('/analytics/export/pdf', [\App\Http\Controllers\AnalyticsController::class, 'exportPdf'])->name('analytics.export.pdf');
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

