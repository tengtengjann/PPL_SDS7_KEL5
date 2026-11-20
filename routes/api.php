<?php

use App\Http\Controllers\MaterialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - EnergiKita (SDG 7)
|--------------------------------------------------------------------------
*/

Route::get('/materials', [MaterialController::class, 'index'])->name('api.materials.index');
