<?php

use App\Http\Controllers\BiayaAdminController;
use App\Http\Controllers\DayaController;
use App\Http\Controllers\KwhMeterController;
use App\Http\Controllers\PascabayarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PICController;
use App\Http\Controllers\PrabayarController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\WitelController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
// });

Route::group([
    'prefix' => 'dashboard',
], function () {
    Route::GET('all-kwh', [DashboardController::class, 'getAllKwhMeter']);
    Route::GET('monthly-kwh', [DashboardController::class, 'getKwhMonthly']);
    Route::GET('kwh-by-regional', [DashboardController::class, 'getKwhByRegional']);
});

Route::group([
    'prefix' => 'prabayar',
], function () {
    Route::GET('export', [PrabayarController::class, 'export']);
    Route::POST('import', [PrabayarController::class, 'import']);
});

Route::group([
    'prefix' => 'pascabayar',
], function () {
    Route::GET('export', [PascabayarController::class, 'export']);
    Route::POST('import', [PascabayarController::class, 'import']);
});

Route::resource('pelanggan', PelangganController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('regional', RegionalController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('meteran', KwhMeterController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
Route::resource('witel', WitelController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('tarif', TarifController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('daya', DayaController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('biaya-admin', BiayaAdminController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('pic', PICController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('prabayar', PrabayarController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('pascabayar', PascabayarController::class)->only(['index', 'store', 'update', 'destroy']);
