<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // API
    // Tenant's API
    public function tenServicesList(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $services = Service::select('id', 'name', 'permit_need', 'photo_url', 'pricePer', 'price', 'availability', 'rating')->where('active_status', 1)->where('tenant_id', $tenant_id)->get();
            if(count($services)>0){
                foreach($services as $svc){
                    $svc->photo_url = "https://gede-darma.my.id/tenants/services/".$svc->photo_url;
                }
                $arrResponse = ["status" => "success", "data"=>$services];
            }
            else{
                $arrResponse = ["status" => "empty"];
            }
        }
        else{
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
        // Keluarkan juga Statusnya Aktif/Gak
    }
    public function tenAddService(Request $request)
    {
    }
}
