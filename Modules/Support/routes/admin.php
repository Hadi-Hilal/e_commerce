<?php

use Illuminate\Support\Facades\Route;
use Modules\Support\app\Http\Controllers\Admin\ContactFormController;
use Modules\Support\app\Http\Controllers\Admin\SubscriberController;

// Subscribers
Route::middleware('can:support.subscribers.view')->group(function () {
    Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
});

Route::middleware('can:support.subscribers.delete')->group(function () {
    Route::delete('subscribers', [SubscriberController::class, 'deleteMulti'])->name('subscribers.deleteMulti');
});

// Contact Forms
Route::middleware('can:support.contact-forms.view')->group(function () {
    Route::get('contact_forms', [ContactFormController::class, 'index'])->name('contact_forms.index');
});

Route::middleware('can:support.contact-forms.delete')->group(function () {
    Route::delete('contact_forms', [ContactFormController::class, 'deleteMulti'])->name('contact_forms.deleteMulti');
});