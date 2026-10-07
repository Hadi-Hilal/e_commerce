<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\Admin\AttributeController;
use Modules\Shop\Http\Controllers\Admin\AttributeFamilyController;

Route::middleware('can:Shop Management')->group(function () {

    Route::delete('attributes/deleteMulti', [AttributeController::class, 'deleteMulti'])->name('attributes.deleteMulti');
    Route::resource('attributes', AttributeController::class)->except(['destroy', 'show']);

    Route::delete('attribute_families/deleteMulti', [AttributeFamilyController::class, 'deleteMulti'])->name('attribute_families.deleteMulti');
    Route::resource('attribute_families', AttributeFamilyController::class)->except(['destroy', 'show']);
});