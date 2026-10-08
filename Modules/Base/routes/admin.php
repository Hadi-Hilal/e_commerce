<?php

use Illuminate\Support\Facades\Route;
use Modules\Base\Http\Controllers\Admin\AdminConfigController;
use Modules\Base\Http\Controllers\Admin\CurrencyController;
use Modules\Base\Http\Controllers\Admin\LogController;
use Modules\Base\Http\Controllers\Admin\MediaLibraryController;
use Modules\Base\Http\Controllers\Admin\SeoController;
use Modules\Base\Http\Controllers\Admin\SiteConfigController;

// Settings Management
Route::middleware('can:settings.website-config.view')->group(function () {
    Route::get('site-configs', [SiteConfigController::class, 'index'])->name('site-configs.index');
});

Route::middleware('can:settings.website-config.create')->group(function () {
    Route::post('site-configs', [SiteConfigController::class, 'store'])->name('site-configs.store');
});

Route::middleware('can:settings.seo.view')->group(function () {
    Route::get('seo', [SeoController::class, 'index'])->name('seo.index');
});

Route::middleware('can:settings.seo.create')->group(function () {
    Route::post('seo', [SeoController::class, 'store'])->name('seo.store');
});

// Currencies
Route::middleware('can:settings.currencies.view')->group(function () {
    Route::get('currencies', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::get('currencies/create', [CurrencyController::class, 'create'])->name('currencies.create');
    Route::get('currencies/{currency}/edit', [CurrencyController::class, 'edit'])->name('currencies.edit');
});

Route::middleware('can:settings.currencies.create')->group(function () {
    Route::post('currencies', [CurrencyController::class, 'store'])->name('currencies.store');
});

Route::middleware('can:settings.currencies.edit')->group(function () {
    Route::put('currencies/{currency}', [CurrencyController::class, 'update'])->name('currencies.update');
});

Route::middleware('can:settings.currencies.delete')->group(function () {
    Route::delete('currencies/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');
    Route::delete('currencies/deleteMulti', [CurrencyController::class, 'deleteMulti'])->name('currencies.deleteMulti');
    Route::post('currencies/{currency}/set-default', [CurrencyController::class, 'setDefault'])->name('currencies.setDefault');
    Route::post('currencies/sync-rates', [CurrencyController::class, 'syncRates'])->name('currencies.syncRates');
});

// Admin Configs (API Configs)
Route::middleware('can:settings.api-config.view')->group(function () {
    Route::get('admin-configs', [AdminConfigController::class, 'index'])->name('admin-configs.index');
});

Route::middleware('can:settings.api-config.create')->group(function () {
    Route::post('admin-configs', [AdminConfigController::class, 'store'])->name('admin-configs.store');
});

// Media Library Management
Route::middleware('can:media-library.view')->group(function () {
    Route::get('media-library', [MediaLibraryController::class, 'index'])->name('media_library.index');
    Route::get('media-library/list', [MediaLibraryController::class, 'list'])->name('media_library.list');
});

Route::middleware('can:media-library.create')->group(function () {
    Route::post('media-library', [MediaLibraryController::class, 'store'])->name('media_library.store');
});

Route::middleware('can:media-library.delete')->group(function () {
    Route::delete('media-library/delete-multi', [MediaLibraryController::class, 'deleteMulti'])->name('media_library.delete_multi');
    Route::delete('media-library/{media}', [MediaLibraryController::class, 'destroy'])->name('media_library.destroy');
});

// Logs Management
Route::middleware('can:logs.view')->group(function () {
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('logs/{log}', [LogController::class, 'show'])->name('logs.show');
});

Route::middleware('can:logs.delete')->group(function () {
    Route::delete('logs/deleteMulti', [LogController::class, 'deleteMulti'])->name('logs.deleteMulti');
});