<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompanyController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->middleware('checkApiKey')->group(function () {
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('company', CompanyController::class);
});
