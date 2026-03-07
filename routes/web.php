<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReportIframeController;
use App\Livewire\ReportViewer;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
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

    Route::get('/report-viewer/{slug}', ReportViewer::class)->name('report.viewer');
});
