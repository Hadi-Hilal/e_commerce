<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\Admin\AttributeController;
use Modules\Shop\Http\Controllers\Admin\AttributeFamilyController;

// Attributes
Route::middleware('can:shop.attributes.view')->group(function () {
    Route::get('attributes', [AttributeController::class, 'index'])->name('attributes.index');
    Route::get('attributes/create', [AttributeController::class, 'create'])->name('attributes.create');
    Route::get('attributes/{attribute}/edit', [AttributeController::class, 'edit'])->name('attributes.edit');
});

Route::middleware('can:shop.attributes.create')->group(function () {
    Route::post('attributes', [AttributeController::class, 'store'])->name('attributes.store');
});

Route::middleware('can:shop.attributes.edit')->group(function () {
    Route::put('attributes/{attribute}', [AttributeController::class, 'update'])->name('attributes.update');
});

Route::middleware('can:shop.attributes.delete')->group(function () {
    Route::delete('attributes/{attribute}', [AttributeController::class, 'destroy'])->name('attributes.destroy');
    Route::delete('attributes/deleteMulti', [AttributeController::class, 'deleteMulti'])->name('attributes.deleteMulti');
});

// Attribute Families
Route::middleware('can:shop.attribute-families.view')->group(function () {
    Route::get('attribute_families', [AttributeFamilyController::class, 'index'])->name('attribute_families.index');
    Route::get('attribute_families/create', [AttributeFamilyController::class, 'create'])->name('attribute_families.create');
    Route::get('attribute_families/{attribute_family}/edit', [AttributeFamilyController::class, 'edit'])->name('attribute_families.edit');
});

Route::middleware('can:shop.attribute-families.create')->group(function () {
    Route::post('attribute_families', [AttributeFamilyController::class, 'store'])->name('attribute_families.store');
});

Route::middleware('can:shop.attribute-families.edit')->group(function () {
    Route::put('attribute_families/{attribute_family}', [AttributeFamilyController::class, 'update'])->name('attribute_families.update');
});

Route::middleware('can:shop.attribute-families.delete')->group(function () {
    Route::delete('attribute_families/{attribute_family}', [AttributeFamilyController::class, 'destroy'])->name('attribute_families.destroy');
    Route::delete('attribute_families/deleteMulti', [AttributeFamilyController::class, 'deleteMulti'])->name('attribute_families.deleteMulti');
});