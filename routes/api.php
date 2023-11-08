<?php

use App\Http\Controllers\PackageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// API General
// Route::get('towerlist', [UnitController::class, 'getTower']);
Route::post('unitbytower', [UnitController::class, 'getUnitNoByTower']);

// Security's App API
Route::post('security/login', [UserController::class, 'securityLogin']);
Route::post('security/checkshift', [UserController::class, 'checkshift']);

Route::post('security/package/pendinglist', [PackageController::class, 'secPackagePendingList']);
Route::post('security/package/detail', [PackageController::class, 'secPackageDetail']);
Route::post('security/package/entry', [PackageController::class, 'secPackageEntry']);
Route::post('security/package/collection', [PackageController::class, 'secPackageCollection']);

Route::post('security/permission/list', [PermissionController::class, 'secPermissionList']);
Route::post('security/permission/detail', [PermissionController::class, 'secPermissionDetail']);
Route::post('security/permission/scan', [PermissionController::class, 'secPermissionScan']);
Route::post('security/permission/savescan', [PermissionController::class, 'secPermissionSaveScan']);
