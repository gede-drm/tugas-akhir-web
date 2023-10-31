<?php

use App\Http\Controllers\PackageController;
use App\Http\Controllers\PermissionController;
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

// Security's App API
Route::get('api/test', function(){
    return ['result'=>'hello!'];
});
Route::post('api/security/login', [UserController::class, 'securityLogin']);
Route::post('api/security/checkshift', [UserController::class, 'checkshift']);

Route::post('api/security/package/pendinglist', [PackageController::class, 'secPackagePendingList']);
Route::post('api/security/package/detail', [PackageController::class, 'secPackageDetail']);
Route::post('api/security/package/entry', [PackageController::class, 'secPackageEntry']);
Route::post('api/security/package/collection', [PackageController::class, 'secPackageCollection']);

Route::post('api/security/permission/list', [PermissionController::class, 'secPermissionList']);
Route::post('api/security/permission/detail', [PermissionController::class, 'secPermissionDetail']);
Route::post('api/security/permission/scan', [PermissionController::class, 'secPermissionScan']);
Route::post('api/security/permission/savescan', [PermissionController::class, 'secPermissionSaveScan']);
