<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TransactionController;
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
Route::post('tenant/registerfcm', [UserController::class, 'tenRegisterFCMToken']);

Route::post('tenant/getprofile', [TenantController::class, 'getTenantProfile']);
Route::post('tenant/getstatus', [TenantController::class, 'getTenantStatus']);
Route::post('tenant/changestatus', [TenantController::class, 'changeTenantStatus']);
Route::post('tenant/revenuesummary', [TenantController::class, 'tenGetRevenueSummary']);

Route::post('tenant/getproducts', [ProductController::class, 'tenProductsList']);
Route::post('tenant/addproduct', [ProductController::class, 'tenAddProduct']);
Route::post('tenant/getproductdetail', [ProductController::class, 'tenGetProductDetail']);
Route::post('tenant/addproductstock', [ProductController::class, 'tenAddProductStock']);
Route::post('tenant/deleteproduct', [ProductController::class, 'tenDeleteProduct']);
Route::post('tenant/updateproduct', [ProductController::class, 'tenUpdateProduct']);

Route::post('tenant/getservices', [ServiceController::class, 'tenServicesList']);
Route::post('tenant/addservice', [ServiceController::class, 'tenAddService']);
Route::post('tenant/getservicedetail', [ServiceController::class, 'tenGetServiceDetail']);
Route::post('tenant/changeserviceavailability', [ServiceController::class, 'tenChangeServiceAvailaibility']);
Route::post('tenant/deleteservice', [ServiceController::class, 'tenDeleteService']);
Route::post('tenant/updateservice', [ServiceController::class, 'tenUpdateService']);

Route::post('tenant/transaction/prorunning', [TransactionController::class, 'tenTrxProductList']);
Route::post('tenant/transaction/svcrunning', [TransactionController::class, 'tenTrxServiceList']);
Route::post('tenant/transaction/prohistory', [TransactionController::class, 'tenTrxProductHistory']);
Route::post('tenant/transaction/svchistory', [TransactionController::class, 'tenTrxServiceHistory']);
Route::post('tenant/transaction/trxprodetail', [TransactionController::class, 'tenTrxProductDetail']);
Route::post('tenant/transaction/trxsvcdetail', [TransactionController::class, 'tenTrxServiceDetail']);
Route::post('tenant/transaction/changestatus', [TransactionController::class, 'tenChangeTransactionStatus']);
Route::post('tenant/transaction/cancel', [TransactionController::class, 'tenCancelTransaction']);
Route::post('tenant/transaction/validatetransfer', [TransactionController::class, 'tenValidateTFProof']);
Route::post('tenant/transaction/proposepermission', [PermissionController::class, 'tenProposePermission']);

// Resident's App API
Route::post('resident/login', [UserController::class, 'residentLogin']);
Route::post('resident/registerfcm', [UserController::class, 'rdtRegisterFCMToken']);

Route::post('resident/unitinfo', [UnitController::class, 'rdtGetUnitInfo']);
Route::post('resident/changewmapref', [UnitController::class, 'rdtChangeWMAPref']);
Route::post('resident/getwmalogs', [UnitController::class, 'rdtGetWMALogs']);

Route::post('resident/package/list', [PackageController::class, 'rdtPackageList']);
Route::post('resident/package/detail', [PackageController::class, 'rdtPackageDetail']);

Route::post('resident/tenant/producttenlist', [TenantController::class, 'rdtProductTenantList']);
Route::post('resident/tenant/servicetenlist', [TenantController::class, 'rdtServiceTenantList']);
Route::post('resident/tenant/tenantitems', [TenantController::class, 'rdtTenantItemList']);
Route::post('resident/tenant/tenantreviews', [TenantController::class, 'rdtGetTenantReview']);
Route::post('resident/tenant/productdetail', [ProductController::class, 'rdtTenProductDetail']);
Route::post('resident/tenant/servicedetail', [ServiceController::class, 'rdtTenServiceDetail']);

Route::post('resident/productcart', [ProductController::class, 'rdtProShoppingCart']);
Route::post('resident/transaction/productcheckout', [TransactionController::class, 'rdtProCheckout']);
Route::post('resident/transaction/getunpaidprotransactions', [TransactionController::class, 'rdtGetUnpaidTransferProTrx']);

Route::post('resident/servicecheckoutlist', [ServiceController::class, 'rdtSvcCheckoutList']);
Route::post('resident/transaction/servicecheckout', [TransactionController::class, 'rdtSvcCheckout']);
Route::post('resident/transaction/getunpaidsvctransaction', [TransactionController::class, 'rdtGetUnpaidTransferSvcTrx']);

Route::post('resident/transaction/uploadtransferproof', [TransactionController::class, 'rdtUploadTransferProof']);
Route::post('resident/transaction/list', [TransactionController::class, 'rdtTransactionList']);
Route::post('resident/transaction/detail', [TransactionController::class, 'rdtTrxDetail']);
Route::post('resident/transaction/getitemtorate', [TransactionController::class, 'rdtGetItemsToRate']);
Route::post('resident/transaction/rateitem', [TransactionController::class, 'rdtSubmitItemsRate']);

Route::post('resident/announcement/get', [AnnouncementController::class, 'rdtGetLatestAnnouncement']);
Route::post('resident/announcement/getoneweek', [AnnouncementController::class, 'rdtGetOneWeekLatestAnnouncement']);