<?php

use Illuminate\Support\Facades\Route;
use Modules\Cms\Http\Controllers\Admin\BlogCategoryController;
use Modules\Cms\Http\Controllers\Admin\BlogController;
use Modules\Cms\Http\Controllers\Admin\FaqController;
use Modules\Cms\Http\Controllers\Admin\PageController;
use Modules\Cms\Http\Controllers\Admin\SlideController;

// Pages
Route::middleware('can:cms.pages.view')->group(function () {
    Route::get('pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::get('pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
});

Route::middleware('can:cms.pages.create')->group(function () {
    Route::post('pages', [PageController::class, 'store'])->name('pages.store');
});

Route::middleware('can:cms.pages.edit')->group(function () {
    Route::put('pages/{page}', [PageController::class, 'update'])->name('pages.update');
});

Route::middleware('can:cms.pages.delete')->group(function () {
    Route::delete('pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
    Route::delete('pages/deleteMulti', [PageController::class, 'deleteMulti'])->name('pages.deleteMulti');
});

// Blogs
Route::middleware('can:cms.blogs.view')->group(function () {
    Route::get('blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::get('blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
});

Route::middleware('can:cms.blogs.create')->group(function () {
    Route::post('blogs', [BlogController::class, 'store'])->name('blogs.store');
});

Route::middleware('can:cms.blogs.edit')->group(function () {
    Route::put('blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
});

Route::middleware('can:cms.blogs.delete')->group(function () {
    Route::delete('blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    Route::delete('blogs/deleteMulti', [BlogController::class, 'deleteMulti'])->name('blogs.deleteMulti');
});

// Blog Categories
Route::middleware('can:cms.blog-categories.view')->group(function () {
    Route::get('blogs_categories', [BlogCategoryController::class, 'index'])->name('blogs_categories.index');
    Route::get('blogs_categories/create', [BlogCategoryController::class, 'create'])->name('blogs_categories.create');
    Route::get('blogs_categories/{blogs_category}/edit', [BlogCategoryController::class, 'edit'])->name('blogs_categories.edit');
});

Route::middleware('can:cms.blog-categories.create')->group(function () {
    Route::post('blogs_categories', [BlogCategoryController::class, 'store'])->name('blogs_categories.store');
});

Route::middleware('can:cms.blog-categories.edit')->group(function () {
    Route::put('blogs_categories/{blogs_category}', [BlogCategoryController::class, 'update'])->name('blogs_categories.update');
});

Route::middleware('can:cms.blog-categories.delete')->group(function () {
    Route::delete('blogs_categories/{blogs_category}', [BlogCategoryController::class, 'destroy'])->name('blogs_categories.destroy');
    Route::delete('blogs_categories/deleteMulti', [BlogCategoryController::class, 'deleteMulti'])->name('blogs_categories.deleteMulti');
});

// FAQs
Route::middleware('can:cms.faqs.view')->group(function () {
    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::get('faqs/create', [FaqController::class, 'create'])->name('faqs.create');
    Route::get('faqs/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
});

Route::middleware('can:cms.faqs.create')->group(function () {
    Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store');
});

Route::middleware('can:cms.faqs.edit')->group(function () {
    Route::put('faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
});

Route::middleware('can:cms.faqs.delete')->group(function () {
    Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');
    Route::delete('faqs/deleteMulti', [FaqController::class, 'deleteMulti'])->name('faqs.deleteMulti');
});

Route::middleware('can:cms.slides.view')->group(function () {
    Route::get('slides', [SlideController::class, 'index'])->name('slides.index');
    Route::get('slides/create', [SlideController::class, 'create'])->name('slides.create');
    Route::get('slides/{slide}/edit', [SlideController::class, 'edit'])->name('slides.edit');
});

Route::middleware('can:cms.slides.create')->group(function () {
    Route::post('slides', [SlideController::class, 'store'])->name('slides.store');
});

Route::middleware('can:cms.slides.edit')->group(function () {
    Route::put('slides/{slide}', [SlideController::class, 'update'])->name('slides.update');
});

Route::middleware('can:cms.slides.delete')->group(function () {
    Route::delete('slides/deleteMulti', [SlideController::class, 'deleteMulti'])->name('slides.deleteMulti');
});
