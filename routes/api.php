<?php

use App\Http\Controllers\MachineController;
use App\Http\Controllers\MachineGameItemController;
use App\Http\Controllers\MachineTypeController;
use Illuminate\Support\Facades\Route;

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

// 機器類型
Route::prefix('machineType')->group(function () {
    Route::get('/{id?}', [MachineTypeController::class, 'get']);
    Route::post('', [MachineTypeController::class, 'create']);
});

// 機器
Route::prefix('machine')->group(function () {
    Route::get('/{machineId?}', [MachineController::class, 'get']);
    Route::post('', [MachineController::class, 'create']);

    // 遊玩紀錄
    Route::prefix('{machineId}/gameItem')->group(function () {
        Route::get('/count', [MachineGameItemController::class, 'getCount']);
        Route::get('/{gameItemId?}', [MachineGameItemController::class, 'get']);
    });
});
