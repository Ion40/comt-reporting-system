<?php
use App\Http\Controllers\Api\APIController;
use App\Http\Controllers\ModuleController;

Route::middleware('auth:sanctum')->group(function () {
    // Ruta para el buscador de Select2
    Route::get('/users/search', [APIController::class, 'search']);
    // Otras rutas de la API
    Route::get('/users/{id}', [APIController::class, 'show'])->name('users.show');
    Route::post('/users', [APIController::class, 'savePermissions'])->name('users.savePermissions');
    Route::get('/modulesParents', [ModuleController::class, 'getModules'])->name('modules.modulesParents');
    Route::get('/getSubmodules/{id}', [ModuleController::class, 'getSubmodules'])->name('modules.getSubmodules');
});
