<?php

use App\Http\Controllers\PackageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SecurityOfficerController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    } else {
        if (Auth::user()->role === 'management') {
            return redirect('/satpam');
        }
        else{
            Auth::logout();
            return redirect('/login');
        }
    }
});

Route::middleware(['auth', 'management'])->group(function () {
    Route::get('satpam', [SecurityOfficerController::class, 'index'])->name('security.index');
    Route::get('satpam/add', [SecurityOfficerController::class, 'add'])->name('security.add');
    Route::post('satpam', [SecurityOfficerController::class, 'store'])->name('security.store');
    Route::post('satpam/deactivate', [SecurityOfficerController::class, 'deactivate'])->name('security.deactivate');
    Route::post('satpam/activate', [SecurityOfficerController::class, 'activate'])->name('security.activate');

    Route::get('satpam/checkin', [SecurityOfficerController::class, 'checkin'])->name('security.checkin');
    Route::get('satpam/checkin/history', [SecurityOfficerController::class, 'checkinHistory'])->name('security.checkinHistory');
    Route::post('satpam/checkin/history', [SecurityOfficerController::class, 'checkinHistoryFilter'])->name('security.checkinHistoryFilter');
    Route::post('satpam/checkin', [SecurityOfficerController::class, 'storeCheckin'])->name('security.storeCheckin');
    Route::post('satpam/checkin/modal', [SecurityOfficerController::class, 'modalCheckin'])->name('security.modalCheckin');
    Route::post('satpam/checkout', [SecurityOfficerController::class, 'storeCheckout'])->name('security.storeCheckout');


    Route::get('perizinan', [PermissionController::class, 'index'])->name('permission.index');
    Route::get('perizinan/detail/{permission}', [PermissionController::class, 'detail'])->name('permission.detail');
    Route::post('perizinan/accept', [PermissionController::class, 'accept'])->name('permission.accept');
    Route::post('perizinan/reject', [PermissionController::class, 'reject'])->name('permission.reject');
    Route::post('perizinan/downloadletter', [PermissionController::class, 'downloadApprovalLetter'])->name('permission.download');

    Route::get('paket', [PackageController::class, 'index'])->name('package.index');
    Route::post('paket/modal', [PackageController::class, 'modalPhoto'])->name('package.modalPhoto');

    Route::get('tenant', [TenantController::class, 'index'])->name('tenant.index');
    Route::get('tenant/add', [TenantController::class, 'add'])->name('tenant.add');
    Route::get('tenant/edit/{tenant}', [TenantController::class, 'edit'])->name('tenant.edit');
    Route::post('tenant', [TenantController::class, 'store'])->name('tenant.store');
    Route::put('tenant/update/{tenant}', [TenantController::class, 'update'])->name('tenant.update');
    Route::post('tenant/deactivate', [TenantController::class, 'deactivate'])->name('tenant.deactivate');
    Route::post('tenant/activate', [TenantController::class, 'activate'])->name('tenant.activate');

    Route::get('unit', [UnitController::class, 'index'])->name('unit.index');
    Route::get('unit/add', [UnitController::class, 'add'])->name('unit.add');
    Route::get('unit/edit/{unit}', [UnitController::class, 'edit'])->name('unit.edit');
    Route::post('unit', [UnitController::class, 'store'])->name('unit.store');
    Route::put('unit/update/{unit}', [UnitController::class, 'update'])->name('unit.update');
    Route::post('unit/deactivate', [UnitController::class, 'deactivateUnit'])->name('unit.deactivate');
    Route::post('unit/activate', [UnitController::class, 'activateUnit'])->name('unit.activate');

    Route::get('tower', [UnitController::class, 'towerIndex'])->name('tower.index');
    Route::get('tower/add', [UnitController::class, 'towerAdd'])->name('tower.add');
    Route::get('tower/edit/{tower}', [UnitController::class, 'towerEdit'])->name('tower.edit');
    Route::post('tower', [UnitController::class, 'towerStore'])->name('tower.store');
    Route::put('tower/update/{tower}', [UnitController::class, 'towerUpdate'])->name('tower.update');
    Route::post('tower/deactivate', [UnitController::class, 'deactivateTower'])->name('tower.deactivate');
    Route::post('tower/activate', [UnitController::class, 'activateTower'])->name('tower.activate');
    
});
