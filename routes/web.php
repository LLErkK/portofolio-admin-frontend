<?php
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\AdminAuthMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;

/**
 * Route Login (tidak pakai middleware)
 */
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

/**
 * Route Group Admin (hanya bisa diakses jika login/admin_token ada)
 */
Route::middleware(AdminAuthMiddleware::class)->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    //profile
    Route::get('/admin/profile',[AdminProfileController::class,'edit'])->name('admin.profile.edit');
    Route::post('/admin/profile',[AdminProfileController::class,'update'])->name('admin.profile.update');

    //project
    Route::get('/admin/project', [AdminProjectController::class, 'index'])->name('admin.project.index');
    Route::get('/admin/project/{id}/edit', [AdminProjectController::class, 'edit'])->name('admin.project.edit');
    Route::post('/admin/project', [AdminProjectController::class, 'store'])->name('admin.project.store');
    Route::post('/admin/project/{id}', [AdminProjectController::class, 'update'])->name('admin.project.update');
    Route::delete('/admin/project/{id}',[AdminProjectController::class,'destroy'])->name('admin.project.destroy');

});


