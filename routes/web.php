<?php

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SecurityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReportIframeController;
use App\Http\Controllers\UsersController;
use App\Livewire\ReportViewer;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get("/Recover", "index")->name("recover.index");
        // Máximo 3 solicitudes de envío por hora por IP
        Route::post("/Recover", "sendResetToken")->name("recover.sendResetToken")->middleware("throttle:password-recovery-send");
        // Máximo 5 intentos de validación de token por minuto
        Route::post("/Recover/validateToken", "validateToken")->name("recover.validateToken")->middleware('throttle:password-recovery-validate');
        Route::post("/Recover/resetPassword", "resetPassword")->name("recover.resetPassword");
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(SecurityController::class)->group(function () {
        Route::get("profile/change-password", "showChangePassword")->name("password.force.change");
        Route::put("profile/change-password", "updatePassword")->name("password.update.custom");
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    Route::controller(PermissionController::class)->group(function () {
        Route::get("/Permission", "index")->name("permission.index");
        Route::get("/underMaintenance", "underMaintenance")->name("permission.mantenimiento");
        Route::get("/costos_import", "costos_import")->name("costos_import");
    });

    Route::controller(ReportIframeController::class)->group(function () {
        Route::get("/Iframes", "index")->name("iframes.index");
        Route::get("/CreateIframe", "createView")->name("iframes.create");
        Route::get('/iframes/{url_path}/edit', 'editIframe')
            ->name('iframes.edit')
            ->where('url_path', '.*'); // Esto permite que el path incluya caracteres especiales o barras
        Route::match(['post', 'put'], '/iframes/save', 'storeOrUpdate')->name('iframes.save');
        Route::put('/iframes/{url_path}/delete', 'deleteIframe')
            ->name('iframes.deleteIframe')
            ->where('url_path', '.*');
    });

    Route::controller(UsersController::class)->group(function () {
        Route::get("/Users", "index")->name("users.index");
        Route::get("/Profile", "userProfile")->name("users.profile");
        Route::match(['post', 'put'], '/users/save', 'storeOrUpdate')->name('users.save');
    });

    Route::controller(ModuleController::class)->group(function () {
        Route::get("/Modules", "index")->name("modules.index");
        Route::match(['post', 'put'], '/modules/save', 'storeOrUpdate')->name('modules.save');
    });

    Route::get('/report-viewer/{slug}', ReportViewer::class)->name('report.viewer');
});
