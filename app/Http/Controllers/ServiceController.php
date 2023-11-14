<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    $sold = DB::select(DB::raw("select sum(std.quantity) as 'sold' from service_transaction_detail std inner join transactions t on std.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where std.service_id = '".$svc->id."' and ts.status='done';"))[0]->sold;
                    if ($sold == null) {
                        $sold = 0;
                    }
                    $svc->sold = $sold;
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
        $tenant_id = $request->get('tenant_id');
        $name = $request->get('name');
        $description = $request->get('description');
        $price = $request->get('price');
        $pricePer = $request->get('pricePer');
        $permit_need = $request->get('permit_need');
        $base64Image = $request->get('image');

        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenantName = Tenant::select('name')->where('id', $tenant_id)->first();
            if ($tenantName != null) {
                $service = new Service();
                $service->name = $name;
                if($permit_need == "true"){
                    $permit_need = 1;
                }
                else{
                    $permit_need = 0;
                }
                $service->permit_need = $permit_need;
                $service->description = $description;
                $service->price = $price;
                $service->pricePer = $pricePer;

                $img = str_replace('data:image/jpeg;base64,', '', $base64Image);
                $img = str_replace(' ', '+', $img);
                $imgData = base64_decode($img);
                $name = str_replace(' ', '-', $name);
                $tenantName = str_replace(' ', '-', $tenantName->name);
                $imgFileName = 'img-service-' . $tenantName . '-' . $name . strtotime('now') . '.png';
                $imgFileDirectory = '../public/tenants/services/' . $imgFileName;
                file_put_contents($imgFileDirectory, $imgData);
                $service->photo_url = $imgFileName;
                $service->availability = 0;
                $service->rating = 0;
                $service->active_status = 1;
                $service->tenant_id = $tenant_id;
                $service->save();
                $arrResponse = ["status" => "success"];
            } else {
                $arrResponse = ["status" => "tenantnotfound"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function tenGetServiceDetail(Request $request){
        $service_id = $request->get('service_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $service = Service::select('id', 'name', 'description', 'permit_need', 'photo_url', 'pricePer', 'price', 'availability', 'rating')->where('id', $service_id)->first();
            $service->photo_url = "https://gede-darma.my.id/tenants/services/".$service->photo_url;
            $sold = DB::select(DB::raw("select sum(std.quantity) as 'sold' from service_transaction_detail std inner join transactions t on std.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where std.service_id = '".$service->id."' and ts.status='done';"))[0]->sold;
            if ($sold == null) {
                $sold = 0;
            }
            $service->sold = $sold;

            $arrResponse = ["status" => "success", "data" => $service];
        }
        else{
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
}
