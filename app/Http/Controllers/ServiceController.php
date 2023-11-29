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
            if (count($services) > 0) {
                foreach ($services as $svc) {
                    $svc->photo_url = Helper::$base_url . "tenants/services/" . $svc->photo_url;
                    $sold = DB::select(DB::raw("select sum(std.quantity) as 'sold' from service_transaction_detail std inner join transactions t on std.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where std.service_id = '" . $svc->id . "' and ts.status='done';"))[0]->sold;
                    if ($sold == null) {
                        $sold = 0;
                    }
                    $svc->sold = $sold;
                }
                $arrResponse = ["status" => "success", "data" => $services];
            } else {
                $arrResponse = ["status" => "empty"];
            }
        } else {
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
                if ($permit_need == "true") {
                    $permit_need = 1;
                } else {
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
                $name = str_replace('/', '-', $name);
                $tenantName = str_replace(' ', '-', $tenantName->name);
                $tenantName = str_replace('/', '-', $tenantName);
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

    public function tenGetServiceDetail(Request $request)
    {
        $service_id = $request->get('service_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $service = Service::select('id', 'name', 'description', 'permit_need', 'photo_url', 'pricePer', 'price', 'availability', 'rating')->where('id', $service_id)->first();
            $service->photo_url = Helper::$base_url . "tenants/services/" . $service->photo_url;
            $sold = DB::select(DB::raw("select sum(std.quantity) as 'sold' from service_transaction_detail std inner join transactions t on std.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where std.service_id = '" . $service->id . "' and ts.status='done';"))[0]->sold;
            if ($sold == null) {
                $sold = 0;
            }
            $service->sold = $sold;

            $arrResponse = ["status" => "success", "data" => $service];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function tenChangeServiceAvailaibility(Request $request)
    {
        $service_id = $request->get('service_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $service = Service::find($service_id);
            if ($service->availability == 0) {
                $service->availability = 1;
            } else {
                $service->availability = 0;
            }
            $service->save();
            $arrResponse = ["status" => "success"];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function tenDeleteService(Request $request)
    {
        $service_id = $request->get('service_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $service = Service::find($service_id);
            $service->active_status = 0;
            $service->save();
            $arrResponse = ["status" => "success"];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function tenUpdateService(Request $request)
    {
        $service_id = $request->get('service_id');
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
            $service = Service::find($service_id);
            if ($service != null) {
                $service->name = $name;
                if ($permit_need == "true") {
                    $permit_need = 1;
                } else {
                    $permit_need = 0;
                }
                $service->permit_need = $permit_need;
                $service->description = $description;
                $service->price = $price;
                $service->pricePer = $pricePer;

                if ($base64Image != "") {
                    unlink("../public/tenants/services/" . $service->photo_url);

                    $img = str_replace('data:image/jpeg;base64,', '', $base64Image);
                    $img = str_replace(' ', '+', $img);
                    $imgData = base64_decode($img);
                    $name = str_replace(' ', '-', $name);
                    $name = str_replace('/', '-', $name);
                    $tenantName = str_replace(' ', '-', $service->tenant->name);
                    $tenantName = str_replace('/', '-', $tenantName);
                    $imgFileName = 'img-service-' . $tenantName . '-' . $name . strtotime('now') . '.png';
                    $imgFileDirectory = '../public/tenants/services/' . $imgFileName;
                    file_put_contents($imgFileDirectory, $imgData);
                    $service->photo_url = $imgFileName;
                }
                $service->save();
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
    public function rdtTenServiceDetail(Request $request)
    {
        $service_id = $request->get('service_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $service = Service::select('id', 'name', 'description', 'photo_url', 'permit_need', 'price', 'pricePer', 'availability', 'rating')->where('id', $service_id)->where('active_status', 1)->first();
            if ($service != null) {
                $service->photo_url = Helper::$base_url . "tenants/services/" . $service->photo_url;
                $sold = DB::select(DB::raw("select sum(std.quantity) as 'sold' from service_transaction_detail std inner join transactions t on std.transaction_id=t.id inner join transaction_statuses ts on ts.transaction_id=t.id where std.service_id = '" . $service->id . "' and ts.status='done';"))[0]->sold;
                if ($sold == null) {
                    $sold = 0;
                }
                $service->sold = $sold;

                $reviews = DB::select(DB::raw("select std.rating, std.review, u.unit_no from service_transaction_detail std inner join transactions t on t.id=std.transaction_id inner join units u on u.id=t.unit_id where std.service_id='" . $service->id . "' and std.rating is not null"));
                if (count($reviews) > 0) {
                    $service->reviewsStatus = "available";
                    $service->reviews = $reviews;
                } else {
                    $service->reviewsStatus = "empty";
                    $service->reviews = 'empty';
                }
                $arrResponse = ["status" => "success", "data" => $service];
            } else {
                $arrResponse = ["status" => "servicenull"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function rdtSvcCheckoutList(Request $request)
    {
        $service_id = $request->get('service_id');
        $service_qty = $request->get('service_qty');

        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            if (isset($service_id) && isset($service_qty)) {
                $service = Service::select('id', 'name', 'permit_need', 'photo_url', 'price', 'pricePer', 'tenant_id')->where('id', $service_id)->where('availability', 1)->where('active_status', 1)->first();
                if ($service != null) {
                    $tenant_delivery = 0;
                    $tenant_cash = 0;
                    $total = 0;

                    if ($service->tenant->delivery == 1) {
                        $tenant_delivery = 1;
                    }
                    if ($service->tenant->cash == 1) {
                        $tenant_cash = 1;
                    }

                    $service->photo_url = Helper::$base_url . "tenants/services/" . $service->photo_url;
                    $service->quantity = $service_qty;

                    $tenant_svctype = $service->tenant->service_type;
                    $tenant_openhour = substr($service->tenant->service_hour_start, 0, 5);
                    $tenant_closehour = substr($service->tenant->service_hour_end, 0, 5);
                    $total = $service->price * $service_qty;
                    $tenant = ["name" => $service->tenant->name, 'tenant_type' => $tenant_svctype, "open_hour" => $tenant_openhour, "close_hour" => $tenant_closehour, "delivery_status" => $tenant_delivery, "cash_status" => $tenant_cash];

                    $service->makeHidden('tenant');

                    $arrResponse = ["status" => "success", "data" => $service, "tenant" => $tenant, "total_payment" => $total];
                } else {
                    $arrResponse = ["status" => "empty"];
                }
            } else {
                $arrResponse = ["status" => "error"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
}
