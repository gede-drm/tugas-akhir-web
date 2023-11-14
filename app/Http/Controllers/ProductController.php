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
                    $pro->photo_url = "https://gede-darma.my.id/tenants/products/" . $pro->photo_url;
                    $sold = DB::select(DB::raw("select sum(ptd.quantity) as 'sold' from product_transaction_detail ptd inner join transactions t on ptd.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where ptd.product_id = 1 and ts.status='done';"))[0]->sold;
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
            $product->photo_url = "https://gede-darma.my.id/tenants/products/" . $product->photo_url;
            $sold = DB::select(DB::raw("select sum(ptd.quantity) as 'sold' from product_transaction_detail ptd inner join transactions t on ptd.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where ptd.product_id = 1 and ts.status='done';"))[0]->sold;
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
}
