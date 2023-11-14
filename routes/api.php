<?php

use App\Http\Controllers\PackageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TenantController;
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
Route::post('cleartoken', [UserController::class, 'cleartoken']);

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
Route::post('security/permission/workersdetail', [PermissionController::class, 'secPermissionWorkersDetail']);
Route::post('security/permission/addpermits', [PermissionController::class, 'secPermissionAddPermits']);

// Tenant's App API
Route::post('tenant/login', [UserController::class, 'tenantLogin']);

Route::post('tenant/getprofile', [TenantController::class, 'getTenantProfile']);
Route::post('tenant/getstatus', [TenantController::class, 'getTenantStatus']);
Route::post('tenant/changestatus', [TenantController::class, 'changeTenantStatus']);

Route::post('tenant/getproducts', [ProductController::class, 'tenProductsList']);
Route::post('tenant/addproduct', [ProductController::class, 'tenAddProduct']);
Route::post('tenant/getproductdetail', [ProductController::class, 'tenGetProductDetail']);
Route::post('tenant/addproductstock', [ProductController::class, 'tenAddProductStock']);
Route::post('tenant/deleteproduct', [ProductController::class, 'tenDeleteProduct']);
Route::post('tenant/updateproduct', [ProductController::class, 'tenUpdateProduct']);

Route::post('tenant/getservices', [ServiceController::class, 'tenServicesList']);
Route::post('tenant/addservice', [ServiceController::class, 'tenAddService']);