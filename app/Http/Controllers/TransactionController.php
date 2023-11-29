<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use Exception;
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
                        $rt->status = TransactionStatus::select('description')->where('transaction_id', $rt->id)->orderBy('date', 'desc')->first()->description;
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
                        $rt->status = TransactionStatus::select('description')->where('transaction_id', $rt->id)->orderBy('date', 'desc')->first()->description;
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

    // Resident's App API
    public function rdtTransactionList(Request $request)
    {
        $unit_id = $request->get('unit_id');
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
    public function rdtTrxProductDetail(Request $request)
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
    public function rdtTrxServiceDetail(Request $request)
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
                    $arrResponse = ["status" => "success"];
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

    public function rdtGetUnpaidTransferTrx(Request $request)
    {
        $unit_id = $request->get('unit_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $notPaidTransactions = Transaction::select('id', 'transaction_date', 'total_payment', 'finish_date', 'tenant_id')->where('payment', 'transfer')->whereNull('payment_proof_url')->where('status', 0)->where('unit_id', $unit_id)->get();
            foreach ($notPaidTransactions as $npt) {
                $npt->tenant_name = $npt->tenant->name;
                $npt->transaction_date = date("d-m-Y H:i", strtotime($npt->transaction_date));
                $npt->finish_date = date("d-m-Y H:i", strtotime($npt->finish_date));
                $npt->bank_name = $npt->tenant->bank_name;
                $npt->account_holder = $npt->tenant->account_holder;
                $npt->account_number = $npt->tenant->bank_account;
                $npt->makeHidden('tenant');
            }
            if (count($notPaidTransactions) > 0) {
                $arrResponse = ["status" => "success", "data" => $notPaidTransactions];
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
            $transactionData = Transaction::select('id', 'payment_proof_url')->where('id', $transaction_id)->whereNull('payment_proof_url')->first();
            if($transactionData != null){
                $date = date('Y-m-d H:i:s');
                $img = str_replace('data:image/jpeg;base64,', '', $base64Image);
                $img = str_replace(' ', '+', $img);
                $imgData = base64_decode($img);
                $imgFileName = 'transferproof-id'.$transactionData->id.'-' . strtotime($date) . '.png';
                $imgFileDirectory = '../public/transactions/transfer-proofs/' . $imgFileName;
                file_put_contents($imgFileDirectory, $imgData);
                $transactionData->payment_proof_url = $imgFileName;
                $transactionData->save();

                $trxStatus = new TransactionStatus();
                $trxStatus->date = $date;
                $trxStatus->status = 'order';
                $trxStatus->description = 'Belum dikonfirmasi';
                $trxStatus->transaction_id = $transactionData->id;
                $trxStatus->save();

                $arrResponse = ["status" => "success"];
            }
            else{
                $arrResponse = ["status" => "emptytrx"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
}
