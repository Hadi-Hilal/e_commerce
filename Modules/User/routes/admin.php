<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\DashboardController;
use Modules\User\Http\Controllers\Admin\ProfileController;
use Modules\User\Http\Controllers\Admin\RoleController;
use Modules\User\Http\Controllers\Admin\StaffController;
use Modules\User\Http\Controllers\Admin\UserController;

Route::middleware('can:dashboard.view')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});

Route::middleware('can:profile.edit')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});

// HR Management - Roles
Route::middleware('can:hr.roles.view')->group(function () {
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
});

Route::middleware('can:hr.roles.create')->group(function () {
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
});

Route::middleware('can:hr.roles.edit')->group(function () {
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
});

Route::middleware('can:hr.roles.delete')->group(function () {
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('roles/delete_role/{id}', [RoleController::class, 'delete_role'])->name('roles.delete_role');
});

Route::middleware('can:hr.roles.edit')->group(function () {
    Route::post('roles/assign_users', [RoleController::class, 'assignUsersToRole'])->name('roles.assign_users');
    Route::post('roles/remove_user_from_role', [RoleController::class, 'removeUserFromRole'])->name('roles.remove_user_from_role');
    Route::post('roles/remove_users_from_role', [RoleController::class, 'removeUsersFromRole'])->name('roles.remove_users_from_role');
});

// HR Management - Staffs
Route::middleware('can:hr.staffs.view')->group(function () {
    Route::get('staffs', [StaffController::class, 'index'])->name('staffs.index');
});

Route::middleware('can:hr.staffs.create')->group(function () {
    Route::post('staffs', [StaffController::class, 'store'])->name('staffs.store');
});

Route::middleware('can:hr.staffs.edit')->group(function () {
    Route::put('staffs/{staff}', [StaffController::class, 'update'])->name('staffs.update');
});

Route::middleware('can:hr.staffs.delete')->group(function () {
    Route::delete('staffs/{staff}', [StaffController::class, 'destroy'])->name('staffs.destroy');
});

// HR Management - Users
Route::middleware('can:hr.users.view')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
});

Route::middleware('can:hr.users.create')->group(function () {
    Route::post('users', [UserController::class, 'store'])->name('users.store');
});

Route::middleware('can:hr.users.edit')->group(function () {
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
});

Route::middleware('can:hr.users.delete')->group(function () {
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});