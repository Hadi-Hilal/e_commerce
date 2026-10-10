<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\Api\AttributeController;
use Modules\Shop\Http\Controllers\Api\AttributeFamilyController;
use Modules\Shop\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|--------------------------------------------------------------------------
*/

// Category API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('categories/parents', [CategoryController::class, 'getParents'])
        ->name('categories.parents');
});