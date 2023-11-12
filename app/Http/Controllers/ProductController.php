<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Product;
use Illuminate\Http\Request;

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
            if(count($products)>0){
                foreach($products as $pro){
                    $pro->photo_url = "https://gede-darma.my.id/tenants/products/".$pro->photo_url;
                }
                $arrResponse = ["status" => "success", "data"=>$products];
            }
            else{
                $arrResponse = ["status" => "empty"];
            }
        }
        else{
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
        // Keluarkan Stoknya
    }
    public function tenAddProduct(Request $request)
    {
    }

    // Detail nanti catat jumlah terjual berapa
}
