<?php

use App\Http\Controllers\Admin\AbilityController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Models\Admin\Ability;
use App\Models\Admin\Role;
use Illuminate\Support\Facades\Route;

Route::bind('role', function ($entityId) {
    $role = Role::findOrFail($entityId);

    return $role;
});

Route::bind('ability', function ($entityId) {
    return Ability::findOrFail($entityId);
});

Route::prefix('admin')->middleware(['auth', 'portico.bouncer'])->group(function () {

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/', [RoleController::class, 'store'])->name('roles.store');
        Route::get('{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    Route::prefix('abilities')->group(function () {
        Route::get('/', [AbilityController::class, 'index'])->name('abilities.index');
        Route::get('/create', [AbilityController::class, 'create'])->name('abilities.create');
        Route::post('/', [AbilityController::class, 'store'])->name('abilities.store');
        Route::get('{ability}', [AbilityController::class, 'show'])->name('abilities.show');
        Route::get('{ability}/edit', [AbilityController::class, 'edit'])->name('abilities.edit');
        Route::put('{ability}', [AbilityController::class, 'update'])->name('abilities.update');
        Route::delete('{ability}', [AbilityController::class, 'destroy'])->name('abilities.destroy');
    });

    Route::prefix('user-permissions')->group(function () {
        Route::get('{user}/edit-user-permissions', [UserPermissionController::class, 'edit'])->name('user-permissions.edit');
        Route::put('{user}/update-user-permissions', [UserPermissionController::class, 'update'])->name('user-permissions.update');
    });
});
