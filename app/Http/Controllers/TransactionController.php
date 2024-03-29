<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\Unit;
use App\Models\User;
use App\Models\WMALog;
use App\Notifications\SendNotification;
use App\Notifications\SendWMA;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\Help;

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
            if ($tenant_type == null) {
                $arrResponse = ["status" => "notenant"];
                return $arrResponse;
            }
            if ($tenant_type->type == 'product') {
                $runningProTrx = Transaction::select('id', 'transaction_date', 'total_payment', 'unit_id')->where('tenant_id', $tenant_id)->where('status', 0)->orderBy('transaction_date', 'asc')->get();
                if (count($runningProTrx) > 0) {
                    foreach ($runningProTrx as $rt) {
                        $rt->unit_no = $rt->unit->unit_no;
                        unset($rt->unit_id);
                        $rt->status = TransactionStatus::select('description')->where('transaction_id', $rt->id)->orderBy('date', 'desc')->first()->description;
                        $rt->item = ['name' => $rt->products[0]->name, 'image' => Helper::$base_url . 'tenants/products/' . $rt->products[0]->photo_url, 'quantity' => $rt->products[0]->pivot->quantity];
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
            if ($tenant_type == null) {
                $arrResponse = ["status" => "notenant"];
                return $arrResponse;
            }
            if ($tenant_type->type == 'service') {
                $runningSvcTrx = Transaction::select('id', 'transaction_date', 'total_payment', 'unit_id')->where('tenant_id', $tenant_id)->where('status', 0)->orderBy('transaction_date', 'asc')->get();
                if (count($runningSvcTrx) > 0) {
                    foreach ($runningSvcTrx as $rt) {
                        $rt->unit_no = $rt->unit->unit_no;
                        unset($rt->unit_id);
                        $rt->status = TransactionStatus::select('description')->where('transaction_id', $rt->id)->orderBy('id', 'desc')->first()->description;
                        $rt->item = ['name' => $rt->services[0]->name, 'image' => Helper::$base_url . 'tenants/services/' . $rt->services[0]->photo_url, 'quantity' => $rt->services[0]->pivot->quantity];
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
            if ($tenant_type == null) {
                $arrResponse = ["status" => "notenant"];
                return $arrResponse;
            }
            if ($tenant_type->type == 'product') {
                $historyProTrx = Transaction::select('id', 'transaction_date', 'total_payment', 'unit_id')->where('tenant_id', $tenant_id)->where('status', 1)->orderBy('transaction_date', 'desc')->get();
                if (count($historyProTrx) > 0) {
                    foreach ($historyProTrx as $rt) {
                        $rt->unit_no = $rt->unit->unit_no;
                        unset($rt->unit_id);
                        $rt->status = TransactionStatus::select('description')->where('transaction_id', $rt->id)->orderBy('id', 'desc')->first()->description;
                        $rt->item = ['name' => $rt->products[0]->name, 'image' => Helper::$base_url . 'tenants/products/' . $rt->products[0]->photo_url, 'quantity' => $rt->products[0]->pivot->quantity];
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
            if ($tenant_type == null) {
                $arrResponse = ["status" => "notenant"];
                return $arrResponse;
            }
            if ($tenant_type->type == 'service') {
                $historySvcTrx = Transaction::select('id', 'transaction_date', 'total_payment', 'unit_id')->where('tenant_id', $tenant_id)->where('status', 1)->orderBy('transaction_date', 'desc')->get();
                if (count($historySvcTrx) > 0) {
                    foreach ($historySvcTrx as $rt) {
                        $rt->unit_no = $rt->unit->unit_no;
                        unset($rt->unit_id);
                        $rt->status = TransactionStatus::select('description')->where('transaction_id', $rt->id)->orderBy('date', 'desc')->first()->description;
                        $rt->item = ['name' => $rt->services[0]->name, 'image' => Helper::$base_url . 'tenants/services/' . $rt->services[0]->photo_url, 'quantity' => $rt->services[0]->pivot->quantity];
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
            $transaction = Transaction::select('id', 'transaction_date', 'delivery', 'payment', 'total_payment', 'payment_proof_url', 'payment_confirm_date', 'finish_date', 'pickup_date', 'status', 'unit_id', 'tenant_id')->where('id', $transaction_id)->with('statuses')->first();
            if ($transaction != null) {
                if ($transaction->tenant->type == "product") {
                    $items = [];
                    $transaction->makeHidden('tenant');
                    $transaction->unit_apart = $transaction->unit->unit_no . " (" . $transaction->unit->holder_name . ")";
                    $transaction->unit_phone = $transaction->unit->holder_ph_number;
                    $transaction->transaction_date = date("d-m-Y H:i", strtotime($transaction->transaction_date));
                    $transaction->finish_date = date("d-m-Y H:i", strtotime($transaction->finish_date));
                    if ($transaction->pickup_date != null) {
                        $transaction->pickup_date = date("d-m-Y H:i", strtotime($transaction->pickup_date));
                    } else {
                        $transaction->pickup_date = "";
                    }
                    if ($transaction->payment == 'transfer') {
                        if ($transaction->payment_proof_url != null) {
                            $transaction->payment_proof_url = Helper::$base_url . 'transactions/transfer-proofs/' . $transaction->payment_proof_url;
                            $transaction->payment_confirm_date = date("d-m-Y H:i", strtotime($transaction->payment_confirm_date));;
                        } else {
                            $transaction->payment_proof_url = "";
                            $transaction->payment_confirm_date = "";
                        }
                    } else {
                        $transaction->payment_proof_url = "";
                        $transaction->payment_confirm_date = "";
                    }
                    foreach ($transaction->products as $tpro) {
                        $items[] = ['id' => $tpro->id, 'name' => $tpro->name, 'photo_url' => Helper::$base_url . 'tenants/products/' . $tpro->photo_url, 'price' => $tpro->pivot->price, 'quantity' => $tpro->pivot->quantity, 'pricePer' => "", 'subtotal' => ($tpro->pivot->price * $tpro->pivot->quantity)];
                    }
                    $transaction->status = TransactionStatus::select('description')->where('transaction_id', $transaction->id)->orderBy('id', 'desc')->first()->description;
                    $transaction->items = $items;
                    foreach ($transaction->statuses as $st) {
                        unset($st->id);
                        unset($st->transaction_id);
                        unset($st->status);
                        $st->date = date("Y-m-d H:i", strtotime($st->date));
                    }
                    $transaction->makeHidden('products');
                    $transaction->makeHidden('unit');
                    $arrResponse = ["status" => "success", "data" => $transaction];
                } else {
                    $arrResponse = ["status" => "wrongtransaction"];
                }
            } else {
                $arrResponse = ["status" => "empty"];
            }
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
            $transaction = Transaction::select('id', 'transaction_date', 'delivery', 'payment', 'total_payment', 'payment_proof_url', 'payment_confirm_date', 'finish_date', 'pickup_date', 'status', 'unit_id', 'tenant_id')->where('id', $transaction_id)->with('statuses')->first();
            if ($transaction != null) {
                if ($transaction->tenant->type == "service") {
                    $items = [];
                    $transaction->makeHidden('tenant');
                    $transaction->svc_type = $transaction->tenant->service_type;
                    $transaction->unit_apart = $transaction->unit->unit_no . " (" . $transaction->unit->holder_name . ")";
                    $transaction->unit_phone = $transaction->unit->holder_ph_number;
                    $transaction->transaction_date = date("Y-m-d H:i", strtotime($transaction->transaction_date));
                    $transaction->finish_date = date("Y-m-d H:i", strtotime($transaction->finish_date));
                    if ($transaction->pickup_date != null) {
                        $transaction->pickup_date = date("Y-m-d H:i", strtotime($transaction->pickup_date));
                    } else {
                        $transaction->pickup_date = "";
                    }
                    if ($transaction->payment == 'transfer') {
                        if ($transaction->payment_proof_url != null) {
                            $transaction->payment_proof_url = Helper::$base_url . 'transactions/transfer-proofs/' . $transaction->payment_proof_url;
                            $transaction->payment_confirm_date = date("d-m-Y H:i", strtotime($transaction->payment_confirm_date));;
                        } else {
                            $transaction->payment_proof_url = "";
                            $transaction->payment_confirm_date = "";
                        }
                    } else {
                        $transaction->payment_proof_url = "";
                        $transaction->payment_confirm_date = "";
                    }
                    foreach ($transaction->services as $tsvc) {
                        $pricePer = $tsvc->pricePer;
                        if ($pricePer == 'hour') {
                            $pricePer = 'Jam';
                        } else {
                            $pricePer = 'Paket';
                        }
                        $items[] = ['id' => $tsvc->id, 'name' => $tsvc->name, 'photo_url' => Helper::$base_url . 'tenants/services/' . $tsvc->photo_url, 'price' => $tsvc->pivot->price, 'quantity' => $tsvc->pivot->quantity, 'pricePer' => $pricePer, 'subtotal' => ($tsvc->pivot->price * $tsvc->pivot->quantity)];
                    }
                    $transaction->status = TransactionStatus::select('description')->where('transaction_id', $transaction->id)->orderBy('id', 'desc')->first()->description;
                    $transaction->items = $items;
                    foreach ($transaction->statuses as $st) {
                        unset($st->id);
                        unset($st->transaction_id);
                        unset($st->status);
                        $st->date = date("Y-m-d H:i", strtotime($st->date));
                    }

                    $transaction->permission_need = $transaction->services[0]->permit_need;
                    if ($transaction->permission_need == 1) {
                        $permission_status = "";
                        $permission = Permission::select('id', 'status', 'approval_date', 'approval_letter_url', 'qr_url')->where('service_transaction_id', $transaction->id)->first();
                        if ($permission == null) {
                            $permission_status = "notproposed";
                        } else {
                            $permission_status = $permission->status;
                            $transaction->permission_approval_date = $permission->approval_date;
                            $transaction->permission_letter = Helper::$base_url . "/permissions/approval-letter/" . $permission->approval_letter_url;
                            $transaction->permission_qr = Helper::$base_url . "/permissions/qr-code/" . $permission->qr_url;
                        }
                    } else {
                        $permission_status = "noneed";
                    }
                    $transaction->permission_status = $permission_status;
                    $transaction->makeHidden('services');
                    $transaction->makeHidden('unit');
                    $arrResponse = ["status" => "success", "data" => $transaction];
                } else {
                    $arrResponse = ["status" => "wrongtransaction"];
                }
            } else {
                $arrResponse = ["status" => "empty"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function tenChangeTransactionStatus(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $statusName = $request->get('statusname');
        $status = $request->get('status');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $transaction = Transaction::find($transaction_id);
            if ($transaction != null) {
                $trxStatus = new TransactionStatus();
                $trxStatus->date = date("Y-m-d H:i:s");
                $trxStatus->status = $statusName;
                $trxStatus->description = $status;
                $trxStatus->transaction_id = $transaction_id;
                $trxStatus->save();

                // Done SvcTrx
                if ($statusName == "done") {
                    $transaction->pickup_date = date("Y-m-d H:i:s");
                    $transaction->status = 1;
                    $transaction->save();

                    $notifTitle = "Pengerjaan Jasa Sudah Selesai";
                    $notifBody = $transaction->tenant->name . " telah selesai melakukan pengerjaan, mohon untuk memberikan rating dari pengerjaan jasa tersebut";

                    $residentUser = $transaction->unit->user;
                    if ($residentUser->fcm_token != null) {
                        try {
                            $residentUser->notify(new SendNotification(["title" => $notifTitle, "body" => $notifBody]));
                        } catch (Exception $e) {
                            Helper::clearFCMToken($residentUser->id);
                        }
                    }
                }

                // Notify User Pro Trx
                if ($status == "Sudah diantar") {
                    $notifTitle = "Barang Sudah Selesai diantar";
                    $notifBody = "Mohon untuk menyelesaikan transaksi dan memberikan rating dari barang-barang yang anda beli dari " . $transaction->tenant->name;

                    $residentUser = $transaction->unit->user;
                    if ($residentUser->fcm_token != null) {
                        try {
                            $residentUser->notify(new SendNotification(["title" => $notifTitle, "body" => $notifBody]));
                        } catch (Exception $e) {
                            Helper::clearFCMToken($residentUser->id);
                        }
                    }

                    foreach ($transaction->products as $tPro) {
                        $this->wmaForecasting($transaction->unit_id, $tPro->id, $tPro->pivot->quantity);
                    }
                } else if ($status == "Sudah diambil") {
                    $notifTitle = "Barang Sudah Selesai Anda Ambil";
                    $notifBody = "Mohon untuk menyelesaikan transaksi dan memberikan rating dari barang-barang yang anda beli dari " . $transaction->tenant->name;

                    $residentUser = $transaction->unit->user;
                    if ($residentUser->fcm_token != null) {
                        try {
                            $residentUser->notify(new SendNotification(["title" => $notifTitle, "body" => $notifBody]));
                        } catch (Exception $e) {
                            Helper::clearFCMToken($residentUser->id);
                        }
                    }

                    foreach ($transaction->products as $tPro) {
                        $this->wmaForecasting($transaction->unit_id, $tPro->id, $tPro->pivot->quantity);
                    }
                }

                $arrResponse = ["status" => "success"];
            } else {
                $arrResponse = ["status" => "notfound"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function tenCancelTransaction(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $transaction = Transaction::find($transaction_id);
            if ($transaction != null) {
                if ($transaction->tenant->type == "product") {
                    foreach ($transaction->products as $tPro) {
                        $pro = Product::find($tPro->id);
                        $pro->stock = $pro->stock + $tPro->pivot->quantity * 1;
                        $pro->save();
                    }
                }
                $transaction->status = 1;
                $trxStatus = new TransactionStatus();
                $trxStatus->date = date("Y-m-d H:i:s");
                $trxStatus->status = "cancelled";
                $trxStatus->description = "Dibatalkan";
                $trxStatus->transaction_id = $transaction_id;

                $transaction->save();
                $trxStatus->save();

                $arrResponse = ["status" => "success"];
            } else {
                $arrResponse = ["status" => "notfound"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function tenValidateTFProof(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $transaction = Transaction::find($transaction_id);
            if ($transaction != null) {
                $transaction->payment_confirm_date = date("Y-m-d H:i:s", strtotime('-2 seconds'));
                $transaction->save();

                $trxStatus = new TransactionStatus();
                $trxStatus->date = date("Y-m-d H:i:s", strtotime('-2 seconds'));
                $trxStatus->status = "transferconfirmed";
                $trxStatus->description = "Pembayaran dikonfirmasi";
                $trxStatus->transaction_id = $transaction_id;
                $trxStatus->save();

                if ($transaction->tenant->type == 'service') {
                    if ($transaction->tenant->service_type == 'other') {
                        $trxStatus = new TransactionStatus();
                        $trxStatus->date = date("Y-m-d H:i:s", strtotime('-1 seconds'));
                        $trxStatus->status = "waiting";
                        $trxStatus->description = "Menunggu Pengerjaan";
                        $trxStatus->transaction_id = $transaction_id;
                        $trxStatus->save();
                    } else {
                        if ($transaction->delivery == "delivery") {
                            $trxStatus = new TransactionStatus();
                            $trxStatus->date = date("Y-m-d H:i:s", strtotime('-1 seconds'));
                            $trxStatus->status = "waiting";
                            $trxStatus->description = "Menunggu Pengambilan";
                            $trxStatus->transaction_id = $transaction_id;
                            $trxStatus->save();
                        } else {
                            $trxStatus = new TransactionStatus();
                            $trxStatus->date = date("Y-m-d H:i:s", strtotime('-1 seconds'));
                            $trxStatus->status = "waiting";
                            $trxStatus->description = "Menunggu Laundry";
                            $trxStatus->transaction_id = $transaction_id;
                            $trxStatus->save();
                        }
                    }
                }
                $arrResponse = ["status" => "success"];
            } else {
                $arrResponse = ["status" => "notfound"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    // Resident's App API
    public function rdtTransactionList(Request $request)
    {
        $unit_id = $request->get('unit_id');
        $searchQuery = '%' . $request->get('search') . '%';
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $transactions = Transaction::select('id', 'transaction_date', 'total_payment', 'tenant_id')->whereRelation('tenant', 'name', 'like', $searchQuery)->where('unit_id', $unit_id)->orderBy('transaction_date', 'desc')->get();
            if (count($transactions) > 0) {
                foreach ($transactions as $trx) {
                    $trx->transaction_date = date('d-m-Y H:i', strtotime($trx->transaction_date));
                    $trx->status = TransactionStatus::select('description')->where('transaction_id', $trx->id)->orderBy('id', 'desc')->first()->description;
                    $trx->tenant_name = $trx->tenant->name;
                    if ($trx->tenant->type == 'service') {
                        $pricePer = $trx->services[0]->pricePer;
                        if ($pricePer == 'hour') {
                            $pricePer = 'Jam';
                        } else {
                            $pricePer = 'Paket';
                        }
                        $trx->item = ['name' => $trx->services[0]->name, 'image' => Helper::$base_url . 'tenants/services/' . $trx->services[0]->photo_url, 'quantity' => $trx->services[0]->pivot->quantity . ' ' . $pricePer];
                        $trx->itemcount = count($trx->services) - 1;
                        $trx->makeHidden('services');
                    } else {
                        $trx->item = ['name' => $trx->products[0]->name, 'image' => Helper::$base_url . 'tenants/products/' . $trx->products[0]->photo_url, 'quantity' => $trx->products[0]->pivot->quantity . ' Barang'];
                        $trx->itemcount = count($trx->products) - 1;
                        $trx->makeHidden('products');
                    }
                    $trx->makeHidden('tenant');
                }
                $arrResponse = ["status" => "success", "data" => $transactions];
            } else {
                $arrResponse = ["status" => "empty"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function rdtTrxDetail(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $transaction = Transaction::select('id', 'transaction_date', 'delivery', 'payment', 'total_payment', 'payment_proof_url', 'payment_confirm_date', 'finish_date', 'pickup_date', 'status', 'tenant_id')->where('id', $transaction_id)->with('statuses')->first();
            if ($transaction != null) {
                $items = [];
                $transaction->tenant_name = $transaction->tenant->name;
                $transaction->transaction_date = date("d-m-Y H:i", strtotime($transaction->transaction_date));
                $transaction->finish_date = date("d-m-Y H:i", strtotime($transaction->finish_date));
                $transaction->tenant_type = $transaction->tenant->type;
                $transaction->svc_type = $transaction->tenant->service_type;
                if ($transaction->svc_type == null) {
                    $transaction->svc_type = "";
                }
                if ($transaction->pickup_date != null) {
                    $transaction->pickup_date = date("d-m-Y H:i", strtotime($transaction->pickup_date));
                } else {
                    $transaction->pickup_date = "";
                }
                if ($transaction->payment == 'transfer') {
                    if ($transaction->payment_proof_url != null) {
                        $transaction->payment_proof_url = Helper::$base_url . 'transactions/transfer-proofs/' . $transaction->payment_proof_url;
                        $transaction->payment_confirm_date = date("d-m-Y H:i", strtotime($transaction->payment_confirm_date));;
                    } else {
                        $transaction->payment_proof_url = "";
                        $transaction->payment_confirm_date = "";
                    }
                } else {
                    $transaction->payment_proof_url = "";
                    $transaction->payment_confirm_date = "";
                }
                if ($transaction->tenant->type == 'product') {
                    foreach ($transaction->products as $tpro) {
                        $items[] = ['id' => $tpro->id, 'name' => $tpro->name, 'photo_url' => Helper::$base_url . 'tenants/products/' . $tpro->photo_url, 'price' => $tpro->pivot->price, 'quantity' => $tpro->pivot->quantity, 'pricePer' => "", 'subtotal' => ($tpro->pivot->price * $tpro->pivot->quantity)];
                    }
                    $transaction->makeHidden('products');
                } else {
                    foreach ($transaction->services as $tsvc) {
                        $pricePer = $tsvc->pricePer;
                        if ($pricePer == 'hour') {
                            $pricePer = 'Jam';
                        } else {
                            $pricePer = 'Paket';
                        }
                        $items[] = ['id' => $tsvc->id, 'name' => $tsvc->name, 'photo_url' => Helper::$base_url . 'tenants/services/' . $tsvc->photo_url, 'price' => $tsvc->pivot->price, 'quantity' => $tsvc->pivot->quantity, 'pricePer' => $pricePer, 'subtotal' => ($tsvc->pivot->price * $tsvc->pivot->quantity)];
                    }
                    if ($transaction->services[0]->pivot->rating != null) {
                        $transaction->rating_done = 1;
                    } else {
                        $transaction->rating_done = 0;
                    }
                    $transaction->permission_need = $transaction->services[0]->permit_need;
                    if ($transaction->permission_need == 1) {
                        $permission = Permission::select('id', 'status', 'approval_date', 'approval_letter_url', 'qr_url')->where('service_transaction_id', $transaction->id)->first();
                        if ($permission == null) {
                            $permission_status = "notproposed";
                        } else {
                            $permission_status = $permission->status;
                            $transaction->permission_approval_date = date('d-m-Y H:i', strtotime($permission->approval_date));
                            $transaction->permission_letter = Helper::$base_url . "/permissions/approval-letter/" . $permission->approval_letter_url;
                            $transaction->permission_qr = Helper::$base_url . "/permissions/qr-code/" . $permission->qr_url;
                        }
                    } else {
                        $permission_status = "noneed";
                    }
                    $transaction->permission_status = $permission_status;
                    $transaction->makeHidden('services');
                }
                $transaction->status = TransactionStatus::select('description')->where('transaction_id', $transaction->id)->orderBy('id', 'desc')->first()->description;
                $transaction->items = $items;
                foreach ($transaction->statuses as $st) {
                    unset($st->id);
                    unset($st->transaction_id);
                    unset($st->status);
                    $st->date = date("d-m-Y H:i", strtotime($st->date));
                }
                $transaction->makeHidden('tenant');
                $arrResponse = ["status" => "success", "data" => $transaction];
            } else {
                $arrResponse = ["status" => "empty"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function rdtProCheckout(Request $request)
    {
        $unit_id = $request->get('unit_id');
        $product_ids = $request->get('product_ids');
        $product_qtys = $request->get('product_qtys');
        $product_prices = $request->get('product_prices');
        $product_tenants = $request->get('product_tenants');
        $tenant_ids = $request->get('tenant_ids');
        $tenant_deliveries = $request->get('tenant_deliveries');
        $tenant_datetimes = $request->get('tenant_datetimes');
        $tenant_paymethods = $request->get('tenant_paymethods');

        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            if (isset($unit_id) && isset($product_ids) && isset($product_qtys) && isset($product_prices) && isset($product_tenants) && isset($tenant_ids) && isset($tenant_deliveries) && isset($tenant_datetimes) && isset($tenant_paymethods)) {
                $temp = [];
                $transferTrxIds = [];
                foreach ($tenant_ids as $key1 => $tenId) {
                    $tenProdArr = [];
                    foreach ($product_ids as $key2 => $proId) {
                        if ($product_tenants[$key2] == $tenId) {
                            $tenProdArr[] = ["product_id" => $proId, "quantity" => $product_qtys[$key2], "price" => $product_prices[$key2]];
                        }
                    }
                    $temp[] = ["tenant_id" => $tenId, "delivery" => $tenant_deliveries[$key1], "datetime" => $tenant_datetimes[$key1], "paymethod" => $tenant_paymethods[$key1], "cart" => $tenProdArr];
                }

                DB::beginTransaction();
                try {
                    foreach ($temp as $tmp) {
                        $date = date('Y-m-d H:i:s');
                        $totalPayment = 0;
                        foreach ($tmp['cart'] as $cart) {
                            $totalPayment += ($cart['quantity'] * $cart['price']);
                        }
                        $transaction = new Transaction();
                        $transaction->transaction_date = $date;
                        $transaction->delivery = $tmp['delivery'];
                        $transaction->payment = $tmp['paymethod'];
                        $transaction->total_payment = $totalPayment;
                        $transaction->finish_date = $tmp['datetime'];
                        $transaction->status = 0;
                        $transaction->unit_id = $unit_id;
                        $transaction->tenant_id = $tmp['tenant_id'];
                        $transaction->save();

                        if ($transaction->payment == "transfer") {
                            $transferTrxIds[] = ["id" => $transaction->id];
                        }

                        foreach ($tmp['cart'] as $cart) {
                            $product = Product::select('id', 'stock')->where('id', $cart['product_id'])->first();
                            if ($product->stock >= $cart['quantity']) {
                                $product->stock = $product->stock - $cart['quantity'] * 1;
                                $product->save();

                                $transaction->products()->attach($cart['product_id'], ['quantity' => $cart['quantity'], 'price' => $cart['price']]);
                            } else {
                                throw new Exception("nostock", 1);
                            }
                        }

                        $transactionStatus = new TransactionStatus();
                        $transactionStatus->transaction_id = $transaction->id;
                        $transactionStatus->date = $date;

                        if ($transaction->payment == 'cash') {
                            $transactionStatus->status = 'order';
                            $transactionStatus->description = 'Belum dikonfirmasi';
                        } else {
                            $transactionStatus->status = 'notransferproof';
                            $transactionStatus->description = 'Belum Pembayaran';
                        }
                        $transactionStatus->save();
                    }
                    DB::commit();

                    foreach ($temp as $tmp) {
                        $ten = Tenant::select('id', 'user_id')->where('id', $tmp['tenant_id'])->first();
                        $tenUser = User::select('id', 'fcm_token')->whereNotNull('fcm_token')->where('id', $ten->user_id)->first();
                        $unit = Unit::select('id', 'unit_no')->where('id', $unit_id)->first();
                        if ($tenUser != null) {
                            $notifTitle = "Anda Mendapat Pesanan Baru!";
                            $notifBody = "Pesanan " . count($tmp['cart']) . " Jenis Barang dari Unit " . $unit->unit_no;
                            try {
                                $tenUser->notify(new SendNotification(["title" => $notifTitle, "body" => $notifBody]));
                            } catch (Exception $e) {
                                Helper::clearFCMToken($tenUser->id);
                            }
                        }
                    }

                    if (count($transferTrxIds) > 0) {
                        $arrResponse = ["status" => "success", "tf" => "yes", "tf_ids" => $transferTrxIds];
                    } else {
                        $arrResponse = ["status" => "success", "tf" => "no"];
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                    if ($e->getMessage() == 'nostock') {
                        $arrResponse = ["status" => "failednostock"];
                    } else {
                        $arrResponse = ["status" => "failed"];
                    }
                }
            } else {
                $arrResponse = ["status" => "error"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function rdtGetUnpaidTransferProTrx(Request $request)
    {
        $unit_id = $request->get('unit_id');
        $transaction_ids = $request->get('transaction_ids');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $notPaidTransactions = Transaction::select('id', 'transaction_date', 'total_payment', 'finish_date', 'tenant_id')->where('payment', 'transfer')->whereNull('payment_proof_url')->where('status', 0)->whereIn('id', $transaction_ids)->where('unit_id', $unit_id)->get();
            if (count($notPaidTransactions) > 0) {
                foreach ($notPaidTransactions as $key => $npt) {
                    if ($npt->tenant->type == 'service') {
                        $notPaidTransactions->forget($key);
                        continue;
                    }
                    $npt->tenant_name = $npt->tenant->name;
                    $npt->transaction_date = date("d-m-Y H:i", strtotime($npt->transaction_date));
                    $npt->finish_date = date("d-m-Y H:i", strtotime($npt->finish_date));
                    $npt->bank_name = $npt->tenant->bank_name;
                    $npt->account_holder = $npt->tenant->account_holder;
                    $npt->account_number = $npt->tenant->bank_account;
                    $npt->makeHidden('tenant');
                }
                if (count($notPaidTransactions) > 0) {
                    $arrResponse = ["status" => "success", "data" => $notPaidTransactions->values()];
                } else {
                    $arrResponse = ["status" => "empty"];
                }
            } else {
                $arrResponse = ["status" => "empty"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function rdtUploadTransferProof(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $base64Image = $request->get('proof_image');

        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $transactionData = Transaction::select('id', 'delivery', 'payment_proof_url', 'tenant_id')->where('id', $transaction_id)->whereNull('payment_proof_url')->first();
            if ($transactionData != null) {
                $date = date('Y-m-d H:i:s');
                $img = str_replace('data:image/jpeg;base64,', '', $base64Image);
                $img = str_replace(' ', '+', $img);
                $imgData = base64_decode($img);
                $imgFileName = 'transferproof-id' . $transactionData->id . '-' . strtotime($date) . '.png';
                $imgFileDirectory = '../public/transactions/transfer-proofs/' . $imgFileName;
                file_put_contents($imgFileDirectory, $imgData);
                $transactionData->payment_proof_url = $imgFileName;
                $transactionData->save();

                $trxStatus = new TransactionStatus();
                $trxStatus->date = $date;
                if ($transactionData->tenant->type == "product") {
                    $trxStatus->status = 'order';
                    $trxStatus->description = 'Belum dikonfirmasi';
                } else {
                    $trxStatus->status = 'payment';
                    $trxStatus->description = 'Pembayaran Belum dikonfirmasi';
                }
                $trxStatus->transaction_id = $transactionData->id;
                $trxStatus->save();

                $arrResponse = ["status" => "success"];
            } else {
                $arrResponse = ["status" => "emptytrx"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function rdtSvcCheckout(Request $request)
    {
        $unit_id = $request->get('unit_id');
        $service_id = $request->get('service_id');
        $service_qty = $request->get('service_qty');
        $service_price = $request->get('service_price');
        $delivery = $request->get('delivery');
        $datetime = $request->get('datetime');
        $paymethod = $request->get('paymethod');

        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            if (isset($unit_id) && isset($service_id) && isset($service_qty) && isset($service_price) && isset($delivery) && isset($datetime) && isset($paymethod)) {
                DB::beginTransaction();
                try {
                    $date = date('Y-m-d H:i:s');
                    $totalPayment = 0;
                    $service = Service::select('id', 'availability', 'tenant_id')->where('id', $service_id)->first();
                    if ($service->availability == 1) {
                        $transaction = new Transaction();
                        $transaction->transaction_date = $date;
                        if ($service->tenant->service_type == 'laundry') {
                            $transaction->delivery = $delivery;
                        } else {
                            $transaction->delivery = 'delivery';
                        }
                        $totalPayment = $service_price * $service_qty;
                        $transaction->payment = $paymethod;
                        $transaction->total_payment = $totalPayment;
                        $transaction->finish_date = $datetime;
                        $transaction->status = 0;
                        $transaction->unit_id = $unit_id;
                        $transaction->tenant_id = $service->tenant_id;
                        $transaction->save();

                        $transaction->services()->attach($service_id, ['quantity' => $service_qty, 'price' => $service_price]);
                    } else {
                        throw new Exception("notavailable", 1);
                    }

                    $transactionStatus = new TransactionStatus();
                    $transactionStatus->transaction_id = $transaction->id;
                    $transactionStatus->date = $date;

                    if ($transaction->payment == 'cash') {
                        $transactionStatus->status = 'order';
                        $transactionStatus->description = 'Belum dikonfirmasi';
                    } else {
                        $transactionStatus->status = 'order';
                        $transactionStatus->description = 'Belum dikonfirmasi';
                    }
                    $transactionStatus->save();

                    DB::commit();

                    $ten = Tenant::select('id', 'user_id')->where('id', $service->tenant_id)->first();
                    $tenUser = User::select('id', 'fcm_token')->whereNotNull('fcm_token')->where('id', $ten->user_id)->first();
                    $unit = Unit::select('id', 'unit_no')->where('id', $unit_id)->first();
                    if ($tenUser != null) {
                        $notifTitle = "Anda Mendapat Pesanan Baru!";
                        $notifBody = "Pesanan dari Unit " . $unit->unit_no;
                        try {
                            $tenUser->notify(new SendNotification(["title" => $notifTitle, "body" => $notifBody]));
                        } catch (Exception $e) {
                            Helper::clearFCMToken($tenUser->id);
                        }
                    }

                    $arrResponse = ["status" => "success", "id" => $transaction->id];
                } catch (Exception $e) {
                    DB::rollBack();
                    if ($e->getMessage() == 'notavailable') {
                        $arrResponse = ["status" => "failednotavailable"];
                    } else {
                        $arrResponse = ["status" => "failed"];
                    }
                }
            } else {
                $arrResponse = ["status" => "error"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function rdtGetUnpaidTransferSvcTrx(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $notPaidTransaction = Transaction::select('id', 'transaction_date', 'total_payment', 'finish_date', 'tenant_id')->where('payment', 'transfer')->whereNull('payment_proof_url')->where('status', 0)->where('id', $transaction_id)->first();
            if ($notPaidTransaction != null) {
                if ($notPaidTransaction->tenant->type == 'service') {
                    $notPaidTransaction->tenant_name = $notPaidTransaction->tenant->name;
                    $notPaidTransaction->transaction_date = date("d-m-Y H:i", strtotime($notPaidTransaction->transaction_date));
                    $notPaidTransaction->finish_date = date("d-m-Y H:i", strtotime($notPaidTransaction->finish_date));
                    $notPaidTransaction->bank_name = $notPaidTransaction->tenant->bank_name;
                    $notPaidTransaction->account_holder = $notPaidTransaction->tenant->account_holder;
                    $notPaidTransaction->account_number = $notPaidTransaction->tenant->bank_account;
                    $notPaidTransaction->makeHidden('tenant');

                    $arrResponse = ["status" => "success", "data" => $notPaidTransaction];
                } else {
                    $arrResponse = ["status" => "wrongtenant"];
                }
            } else {
                $arrResponse = ["status" => "empty"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function rdtGetItemsToRate(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $transaction = Transaction::find($transaction_id);
            if ($transaction != null) {
                $items = [];
                if ($transaction->tenant->type == "product") {
                    foreach ($transaction->products as $pro) {
                        $items[] = ["id" => $pro->id, "name" => $pro->name];
                    }
                } else {
                    foreach ($transaction->services as $svc) {
                        $items[] = ["id" => $svc->id, "name" => $svc->name];
                    }
                }
                if (count($items) > 0) {
                    $arrResponse = ["status" => "success", "data" => $items];
                } else {
                    $arrResponse = ["status" => "empty"];
                }
            } else {
                $arrResponse = ["status" => "notfound"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function rdtSubmitItemsRate(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $items_id = $request->get('items_id');
        $items_rating = $request->get('items_rating');
        $items_review = $request->get('items_review');
        $service_rating = $request->get('service_rating');
        $service_review = $request->get('service_review');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $transaction = Transaction::find($transaction_id);
            if ($transaction != null) {
                if (isset($transaction_id) && isset($items_id) && isset($items_rating) && isset($items_review)) {
                    if ($transaction->tenant->type == "product") {
                        if ($transaction->status == 0) {
                            foreach ($items_id as $key => $id) {
                                $transaction->products()->updateExistingPivot(
                                    $id,
                                    [
                                        "rating" => $items_rating[$key],
                                        "review" => $items_review[$key]
                                    ]
                                );

                                $newRating = DB::select(DB::raw("select round(avg(rating),2) as 'rating' from product_transaction_detail where product_id='" . $id . "' and rating is not null"))[0]->rating;
                                DB::update("update products set rating='" . $newRating . "' where id='" . $id . "'");
                            }
                            $transaction->pickup_date = date('Y-m-d H:i:s');
                            $transaction->status = 1;
                            $transaction->service_rating = $service_rating;
                            $transaction->service_review = $service_review;
                            $transaction->save();

                            $trxStatus = new TransactionStatus();
                            $trxStatus->date = date("Y-m-d H:i:s");
                            $trxStatus->status = 'done';
                            $trxStatus->description = 'Selesai';
                            $trxStatus->transaction_id = $transaction->id;
                            $trxStatus->save();

                            $notifTitle = "Transaksi Berhasil diselesaikan";
                            $notifBody = "Terima kasih telah berbelanja! Terima kasih juga untuk rating yang telah anda berikan";

                            try {
                                $residentUser = $transaction->unit->user;
                                $residentUser->notify(new SendNotification(["title" => $notifTitle, "body" => $notifBody]));
                            } catch (Exception $e) {
                                $residentUser = User::find($transaction->unit->user->id);
                                $residentUser->fcm_token = null;
                                $residentUser->save();
                            }

                            $arrResponse = ["status" => "success"];
                        } else {
                            $arrResponse = ["status" => "finish"];
                        }
                    } else {
                        foreach ($items_id as $key => $id) {
                            $transaction->services()->updateExistingPivot(
                                $id,
                                [
                                    "rating" => $items_rating[$key],
                                    "review" => $items_review[$key]
                                ]
                            );

                            $newRating = DB::select(DB::raw("select round(avg(rating),2) as 'rating' from service_transaction_detail where service_id='" . $id . "' and rating is not null"))[0]->rating;
                            DB::update("update services set rating='" . $newRating . "' where id='" . $id . "'");
                        }

                        $notifTitle = "Rating diterima";
                        $notifBody = "Terima kasih telah berbelanja! Terima kasih juga untuk rating yang telah anda berikan";

                        $residentUser = $transaction->unit->user;
                        if ($residentUser->fcm_token != null) {
                            try {
                                $residentUser->notify(new SendNotification(["title" => $notifTitle, "body" => $notifBody]));
                            } catch (Exception $e) {
                                Helper::clearFCMToken($residentUser->id);
                            }
                        }

                        $arrResponse = ["status" => "success"];
                    }
                } else {
                    $arrResponse = ["status" => "error"];
                }
            } else {
                $arrResponse = ["status" => "notfound"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    private function wmaForecasting($unit_id, $product_id, $quantity)
    {
        $unit = Unit::select('id', 'wma_preference', 'user_id')->where('id', $unit_id)->first();

        $proTrxData = DB::select(DB::raw("select ptd.product_id, sum(ptd.quantity) as 'qty', ts.date from product_transaction_detail ptd inner join transactions t on ptd.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where (ts.status='pickedup' or ts.status='delivered') and ptd.product_id = '" . $product_id . "' and t.unit_id='" . $unit_id . "' group by ts.date, ptd.product_id order by date desc limit " . $unit->wma_preference . ";"));
        if ($unit->wma_preference > 0) {
            if (count($proTrxData) == $unit->wma_preference) {
                $datetimediff = [];
                foreach ($proTrxData as $key => $data) {
                    if ($key == 0) {
                        $datetimediff[] = ((strtotime(date('Y-m-d'))) - (strtotime(date('Y-m-d', strtotime($data->date))))) / $data->qty;
                        $datetimediff[] = ((strtotime(date('Y-m-d',strtotime($data->date)))) - (strtotime(date('Y-m-d', strtotime($proTrxData[$key + 1]->date))))) / $data->qty;
                    } else {
                        if ($key < count($proTrxData)-1) {
                            $datetimediff[] = (strtotime(date('Y-m-d',(strtotime($data->date)))) - (strtotime(date('Y-m-d',strtotime($proTrxData[$key + 1]->date))))) / $data->qty;
                        }
                    }
                }

                $totalWMA = 0;
                $totalWeight = 0;
                for ($i = 1; $i <= $unit->wma_preference; $i++) {
                    $totalWeight = $totalWeight + $i;
                }

                for ($i = 1; $i <= $unit->wma_preference; $i++) {
                    $totalWMA = $totalWMA + ($i * ($datetimediff[$i - 1]));
                }

                $resultWMA = floor($totalWMA / $totalWeight) * $quantity;

                $userResident = $unit->user;
                $productName = Product::select('name')->where('id', $product_id)->first();
                $title = "Jangan Lupa untuk Beli Kebutuhanmu";
                $body = "Beli " . $productName->name . " Sekarang!";
                if ($userResident->fcm_token != null) {
                    try {
                        $delay = now()->addSeconds($resultWMA);
                        $userResident->notify((new SendNotification(['title' => $title, 'body' => $body]))->delay($delay));

                        $wmaLog = new WMALog();
                        $wmaLog->date = date('Y-m-d H:i:s');
                        $wmaLog->send_date = date('Y-m-d H:i:s', (strtotime(date('Y-m-d H:i:s')) + $resultWMA));
                        $wmaLog->description = "Sudah Saatnya untuk membeli " . $productName->name . " kembali!";
                        $wmaLog->unit_id = $unit_id;
                        $wmaLog->save();
                    } catch (Exception $e) {
                        Helper::clearFCMToken($userResident->id);
                    }
                }
            }
        }
    }
}
