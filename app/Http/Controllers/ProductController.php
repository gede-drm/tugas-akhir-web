<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //API
    // Tenant's API
    public function tenProductsList(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $products = Product::select('id', 'name', 'photo_url', 'price', 'stock', 'rating')->where('active_status', 1)->where('tenant_id', $tenant_id)->get();
            if (count($products) > 0) {
                foreach ($products as $pro) {
                    $pro->photo_url = Helper::$base_url . "tenants/products/" . $pro->photo_url;
                    $sold = DB::select(DB::raw("select sum(ptd.quantity) as 'sold' from product_transaction_detail ptd inner join transactions t on ptd.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where ptd.product_id = '" . $pro->id . "' and ts.status='done';"))[0]->sold;
                    if ($sold == null) {
                        $sold = 0;
                    }
                    $pro->sold = $sold;
                }
                $arrResponse = ["status" => "success", "data" => $products];
            } else {
                $arrResponse = ["status" => "empty"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function tenAddProduct(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $name = $request->get('name');
        $description = $request->get('description');
        $price = $request->get('price');
        $base64Image = $request->get('image');

        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenantName = Tenant::select('name')->where('id', $tenant_id)->first();
            if ($tenantName != null) {
                $product = new Product();
                $product->name = $name;
                $product->description = $description;
                $product->price = $price;

                $img = str_replace('data:image/jpeg;base64,', '', $base64Image);
                $img = str_replace(' ', '+', $img);
                $imgData = base64_decode($img);
                $name = str_replace(' ', '-', $name);
                $tenantName = str_replace(' ', '-', $tenantName->name);
                $imgFileName = 'img-product-' . $tenantName . '-' . $name . strtotime('now') . '.png';
                $imgFileDirectory = '../public/tenants/products/' . $imgFileName;
                file_put_contents($imgFileDirectory, $imgData);
                $product->photo_url = $imgFileName;
                $product->stock = 0;
                $product->rating = 0;
                $product->active_status = 1;
                $product->tenant_id = $tenant_id;
                $product->save();
                $arrResponse = ["status" => "success"];
            } else {
                $arrResponse = ["status" => "tenantnotfound"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function tenGetProductDetail(Request $request)
    {
        $product_id = $request->get('product_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $product = Product::select('id', 'name', 'description', 'photo_url', 'price', 'stock', 'rating')->where('id', $product_id)->first();
            $product->photo_url = Helper::$base_url . "tenants/products/" . $product->photo_url;
            $sold = DB::select(DB::raw("select sum(ptd.quantity) as 'sold' from product_transaction_detail ptd inner join transactions t on ptd.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where ptd.product_id = '" . $product_id . "' and ts.status='done';"))[0]->sold;
            if ($sold == null) {
                $sold = 0;
            }
            $product->sold = $sold;
            $arrResponse = ["status" => "success", "data" => $product];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function tenAddProductStock(Request $request)
    {
        $product_id = $request->get('product_id');
        $stockValue = $request->get('stock_added');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $product = Product::find($product_id);
            $product->stock = $product->stock + ($stockValue * 1);
            $product->save();
            $arrResponse = ["status" => "success"];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function tenDeleteProduct(Request $request)
    {
        $product_id = $request->get('product_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $product = Product::find($product_id);
            $product->active_status = 0;
            $product->save();
            $arrResponse = ["status" => "success"];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function tenUpdateProduct(Request $request)
    {
        $product_id = $request->get('product_id');
        $name = $request->get('name');
        $description = $request->get('description');
        $price = $request->get('price');
        $base64Image = $request->get('image');

        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $product = Product::find($product_id);
            if ($product != null) {
                $product->name = $name;
                $product->description = $description;
                $product->price = $price;
                if ($base64Image != "") {
                    unlink("../public/tenants/products/" . $product->photo_url);

                    $img = str_replace('data:image/jpeg;base64,', '', $base64Image);
                    $img = str_replace(' ', '+', $img);
                    $imgData = base64_decode($img);
                    $name = str_replace(' ', '-', $name);
                    $tenantName = str_replace(' ', '-', $product->tenant->name);
                    $imgFileName = 'img-product-' . $tenantName . '-' . $name . strtotime('now') . '.png';
                    $imgFileDirectory = '../public/tenants/products/' . $imgFileName;
                    file_put_contents($imgFileDirectory, $imgData);
                    $product->photo_url = $imgFileName;
                }
                $product->save();
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
    public function rdtTenProductDetail(Request $request)
    {
        $product_id = $request->get('product_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $product = Product::select('id', 'name', 'description', 'photo_url', 'price', 'stock', 'rating')->where('id', $product_id)->where('active_status', 1)->first();
            if ($product != null) {
                $product->photo_url = Helper::$base_url . "tenants/products/" . $product->photo_url;
                $sold = DB::select(DB::raw("select sum(ptd.quantity) as 'sold' from product_transaction_detail ptd inner join transactions t on ptd.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where ptd.product_id = '" . $product->id . "' and ts.status='done';"))[0]->sold;
                if ($sold == null) {
                    $sold = 0;
                }
                $product->sold = $sold;

                $reviews = DB::select(DB::raw("select ptd.rating, ptd.review, u.unit_no from product_transaction_detail ptd inner join transactions t on t.id=ptd.transaction_id inner join units u on u.id=t.unit_id where ptd.product_id='" . $product->id . "' and ptd.rating is not null"));
                if (count($reviews) > 0) {
                    $product->reviewsStatus = "available";
                    $product->reviews = $reviews;
                } else {
                    $product->reviewsStatus = "empty";
                    $product->reviews = 'empty';
                }
                $arrResponse = ["status" => "success", "data" => $product];
            } else {
                $arrResponse = ["status" => "productnull"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function rdtProShoppingCart(Request $request)
    {
        $product_ids = $request->get('product_ids');
        $product_qtys = $request->get('product_qtys');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if (isset($product_ids) && isset($product_qtys)) {
            if ($tokenValidation == true) {
                $emptyStatus = 'empty';
                $empty = [];
                $data = [];
                $tenant_delivery = [];
                $total = 0;
                foreach ($product_ids as $key => $id) {
                    $product = Product::select('id', 'name', 'photo_url', 'price', 'stock', 'tenant_id')->where('id', $id)->where('active_status', 1)->first();
                    if ($product != null) {
                        if ($product->stock == 0) {
                            $empty[] = $id;
                            $emptyStatus = 'true';
                        } else {
                            if ($product->stock >= $product_qtys[$key]) {
                                $product->photo_url = Helper::$base_url . "tenants/products/" . $product->photo_url;
                                $product->qty = $product_qtys[$key];
                                $product->subtotal = $product->price * $product_qtys[$key];
                                $product->tenant_name = $product->tenant->name;
                                $product->cash = $product->tenant->cash;
                                $tenant_delivery[] = ["id"=>$product->tenant_id, "tenant"=>$product->tenant->name, "cash"=>$product->tenant->cash, "delivery"=>$product->tenant->delivery, "open_hour"=>substr($product->tenant->service_hour_start, 0, 5), "close_hour"=>substr($product->tenant->service_hour_end, 0, 5)];
                                $product->makeHidden('tenant');
                                $data[] = $product;
                                $total += $product->subtotal;
                            } else {
                                $empty[] = $id;
                                $emptyStatus = 'true';
                            }
                        }
                    } else {
                        $empty[] = $id;
                        $emptyStatus = 'true';
                    }
                }
                if (count($data) > 0) {
                    $data = collect($data);
                    $data->sortBy('tenant_name');

                    $tenant_delivery = collect($tenant_delivery);
                    $tenant_delivery = $tenant_delivery->unique("id");
                    $tenant_delivery = collect($tenant_delivery)->toArray();
                    $arrResponse = ["status" => "success", "emptyStatus" => $emptyStatus, "emptyids" => $empty, "data" => $data, "total" => $total, "delivery"=>$tenant_delivery];
                } else {
                    $arrResponse = ["status" => "allempty"];
                }
            } else {
                $arrResponse = ["status" => "notauthenticated"];
            }
        } else {
            $arrResponse = ["status" => "error"];
        }
        return $arrResponse;
    }
}
