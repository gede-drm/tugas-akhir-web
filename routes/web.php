<?php

use App\Http\Controllers\PermitController;
use App\Http\Controllers\SecurityOfficerController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UnitController;
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

Route::get('/', function () {
    return redirect('satpam');
});

Route::get('satpam', [SecurityOfficerController::class, 'index'])->name('security.index');
Route::get('satpam/add', [SecurityOfficerController::class, 'add'])->name('security.add');
Route::post('satpam', [SecurityOfficerController::class, 'store'])->name('security.store');

Route::get('perizinan', [PermitController::class, 'index'])->name('permit.index');

Route::get('tenant', [TenantController::class, 'index'])->name('tenant.index');
Route::get('tenant/add', [TenantController::class, 'add'])->name('tenant.add');
Route::post('tenant', [TenantController::class, 'store'])->name('tenant.store');

Route::get('unit', [UnitController::class, 'index'])->name('unit.index');
Route::get('unit/add', [UnitController::class, 'add'])->name('unit.add');
Route::get('unit/edit/{unit}', [UnitController::class, 'edit'])->name('unit.edit');
Route::post('unit', [UnitController::class, 'store'])->name('unit.store');
Route::put('unit/update/{unit}', [UnitController::class, 'update'])->name('unit.update');
