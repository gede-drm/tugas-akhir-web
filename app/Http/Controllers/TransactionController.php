<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // API
    // Tenant's API
    public function tenTrxProductList(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenant_type = Tenant::select('type')->where('id', $tenant_id)->first();
            if($tenant_type == null){
                $arrResponse = ["status" => "notenant"];
                return $arrResponse;
            }
            if ($tenant_type->type == 'product') {
                $runningProTrx = Transaction::select('id', 'transaction_date', 'total_payment', 'unit_id')->where('tenant_id', $tenant_id)->where('status', 0)->get();
                if (count($runningProTrx) > 0) {
                    foreach ($runningProTrx as $rt) {
                        $rt->unit_no = $rt->unit->unit_no;
                        unset($rt->unit_id);
                        $rt->status = TransactionStatus::select('description')->where('transaction_id', $rt->id)->orderBy('date', 'desc')->first()->description;
                        $rt->item = ['name' => $rt->products[0]->name,'image'=>Helper::$base_url.'tenants/products/' . $rt->products[0]->photo_url, 'quantity' => $rt->products[0]->pivot->quantity];
                        $rt->itemcount = count($rt->products) - 1;
                    }
                    $runningProTrx->makeHidden('unit');
                    $runningProTrx->makeHidden('products');
                    $arrResponse = ["status" => "success", "data" => $runningProTrx];
                } else {
                    $arrResponse = ["status" => "empty"];
                }
            } else {
                $arrResponse = ["status" => "wrongtenant"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function tenTrxServiceList(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenant_type = Tenant::select('type')->where('id', $tenant_id)->first();
            if($tenant_type == null){
                $arrResponse = ["status" => "notenant"];
                return $arrResponse;
            }
            if ($tenant_type->type == 'service') {
                $runningSvcTrx = Transaction::select('id', 'transaction_date', 'total_payment', 'unit_id')->where('tenant_id', $tenant_id)->where('status', 0)->get();
                if (count($runningSvcTrx) > 0) {
                    foreach ($runningSvcTrx as $rt) {
                        $rt->unit_no = $rt->unit->unit_no;
                        unset($rt->unit_id);
                        $rt->status = TransactionStatus::select('description')->where('transaction_id', $rt->id)->orderBy('date', 'desc')->first()->description;
                        $rt->item = ['name' => $rt->services[0]->name,'image'=>Helper::$base_url.'tenants/services/' . $rt->services[0]->photo_url, 'quantity' => $rt->services[0]->pivot->quantity];
                        $rt->itemcount = count($rt->services) - 1;
                    }
                    $runningSvcTrx->makeHidden('unit');
                    $runningSvcTrx->makeHidden('services');
                    $arrResponse = ["status" => "success", "data" => $runningSvcTrx];
                } else {
                    $arrResponse = ["status" => "empty"];
                }
            } else {
                $arrResponse = ["status" => "wrongtenant"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function tenTrxProductHistory(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenant_type = Tenant::select('type')->where('id', $tenant_id)->first();
            if($tenant_type == null){
                $arrResponse = ["status" => "notenant"];
                return $arrResponse;
            }
            if ($tenant_type->type == 'product') {
                $historyProTrx = Transaction::select('id', 'transaction_date', 'total_payment', 'unit_id')->where('tenant_id', $tenant_id)->where('status', 1)->get();
                if (count($historyProTrx) > 0) {
                    foreach ($historyProTrx as $rt) {
                        $rt->unit_no = $rt->unit->unit_no;
                        unset($rt->unit_id);
                        $rt->status = TransactionStatus::select('description')->where('transaction_id', $rt->id)->orderBy('date', 'desc')->first()->description;
                        $rt->item = ['name' => $rt->products[0]->name,'image'=>Helper::$base_url.'tenants/products/' . $rt->products[0]->photo_url, 'quantity' => $rt->products[0]->pivot->quantity];
                        $rt->itemcount = count($rt->products) - 1;
                    }
                    $historyProTrx->makeHidden('unit');
                    $historyProTrx->makeHidden('products');
                    $arrResponse = ["status" => "success", "data" => $historyProTrx];
                } else {
                    $arrResponse = ["status" => "empty"];
                }
            } else {
                $arrResponse = ["status" => "wrongtenant"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function tenTrxServiceHistory(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenant_type = Tenant::select('type')->where('id', $tenant_id)->first();
            if($tenant_type == null){
                $arrResponse = ["status" => "notenant"];
                return $arrResponse;
            }
            if ($tenant_type->type == 'service') {
                $historySvcTrx = Transaction::select('id', 'transaction_date', 'total_payment', 'unit_id')->where('tenant_id', $tenant_id)->where('status', 1)->get();
                if (count($historySvcTrx) > 0) {
                    foreach ($historySvcTrx as $rt) {
                        $rt->unit_no = $rt->unit->unit_no;
                        unset($rt->unit_id);
                        $rt->status = TransactionStatus::select('description')->where('transaction_id', $rt->id)->orderBy('date', 'desc')->first()->description;
                        $rt->item = ['name' => $rt->services[0]->name,'image'=>Helper::$base_url.'tenants/services/' . $rt->services[0]->photo_url, 'quantity' => $rt->services[0]->pivot->quantity];
                        $rt->itemcount = count($rt->services) - 1;
                    }
                    $historySvcTrx->makeHidden('unit');
                    $historySvcTrx->makeHidden('services');
                    $arrResponse = ["status" => "success", "data" => $historySvcTrx];
                } else {
                    $arrResponse = ["status" => "empty"];
                }
            } else {
                $arrResponse = ["status" => "wrongtenant"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function tenTrxProductDetail(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            // TODO
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function tenTrxServiceDetail(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            // TODO
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
}
