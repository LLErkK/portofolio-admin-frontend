<?php
use App\Http\Controllers\AdminCertificateController;
use App\Http\Controllers\AdminEducationController;
use App\Http\Controllers\AdminExperienceController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\AdminAuthMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    if (Session::has('admin_token')) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('admin.login');
    }
});

/**
 * Route Login (tidak pakai middleware)
 */
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('admin/register',[AdminAuthController::class,'showRegister'])->name('admin.register');
Route::post('/admin/register',[AdminAuthController::class,'register'])->name('admin.register.submit');

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

    //experience
    Route::get('/admin/experience',[AdminExperienceController::class,'get'])->name('admin.experience.index');
    Route::post('/admin/experience',[AdminExperienceController::class,'store'])->name('admin.experience.store');
    Route::get('/admin/experience/{id}',[AdminExperienceController::class,'edit'])->name('admin.experience.edit');
    Route::put('/admin/experience/{id}',[AdminExperienceController::class,'update'])->name('admin.experience.update');
    Route::delete('/admin/experience/{id}', [AdminExperienceController::class, 'destroy'])->name('admin.experience.destroy');
    
    //education
    Route::get('/admin/education',[AdminEducationController::class,'get'])->name('admin.education.index');
    Route::get('/admin/education/{id}',[AdminEducationController::class,'edit'])->name('admin.education.edit');
    Route::post('/admin/education',[AdminEducationController::class,'store'])->name('admin.education.store');
    Route::patch('/admin/education/{id}',[AdminEducationController::class,'update'])->name('admin.education.update');
    Route::delete('/admin/education/{id}',[AdminEducationController::class,'destroy'])->name('admin.education.destroy');

    //certificate
    Route::get('/admin/certificate',[AdminCertificateController::class,'get'])->name('admin.certificate.index');
    Route::get('/admin/certificate/{id}',[AdminCertificateController::class,'edit'])->name('admin.certificate.edit');
    Route::post('/admin/certificate',[AdminCertificateController::class,'store'])->name('admin.certificate.store');
    Route::put('/admin/certificate/{id}',[AdminCertificateController::class,'update'])->name('admin.certificate.update');
    Route::delete('/admin/certificate/{id}',[AdminCertificateController::class,'destroy'])->name('admin.certificate.destroy');
    
});
Route::fallback(function () {
    if (Session::has('admin_token')) {
        return redirect()->route('admin.dashboard')->with('error', '404 halaman tidak tersedia');
    } else {
        return redirect()->route('admin.login')->with('error', '404 halaman tidak tersedia');
    }
});




