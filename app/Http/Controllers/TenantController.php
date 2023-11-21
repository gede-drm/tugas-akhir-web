<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    // Web
    public function index()
    {
        $activeTenants = Tenant::where('active_status', 1)->orderBy('name', 'asc')->get();
        $nonactiveTenants = Tenant::where('active_status', 0)->orderBy('name', 'asc')->get();
        return view('tenant.index', compact('activeTenants', 'nonactiveTenants'));
    }
    public function add()
    {
        return view('tenant.add');
    }
    public function store(Request $request)
    {
        $request->validate(["tenant_name" => "required", "tenant_address" => "required", "phone_number" => "required", "type" => "required", "opening_hour" => "required", "closing_hour" => "required", "bank_name" => "required", "bank_account" => "required", "bank_holder" => "required", "delivery" => "required", "username" => "required|unique:users,username", "password" => "required|min:8", "conf_pass" => "required|same:password"], ["conf_pass.same" => "Konfirmasi Password Tidak Sesuai!"]);

        User::create([
            "username" => $request->get('username'),
            "password" => Hash::make($request->get('password')),
            "role" => "tenant"
        ]);

        $userId = User::select('id')->where('username', $request->get('username'))->first();
        $newTenant = new Tenant();
        $newTenant->name = $request->get('tenant_name');
        $newTenant->address = $request->get('tenant_address');
        $newTenant->phone_number = $request->get('phone_number');
        if ($request->get('type') == 'product') {
            $newTenant->type = 'product';
        }
        if ($request->get('type') == 'servive') {
            $newTenant->type = 'service';
        }
        $newTenant->service_hour_start = $request->get('opening_hour');
        $newTenant->service_hour_end = $request->get('closing_hour');
        $newTenant->bank_name = $request->get('bank_name');
        $newTenant->bank_account = $request->get('bank_account');
        $newTenant->account_holder = $request->get('bank_holder');
        if ($request->get('delivery') == 'yes') {
            $newTenant->delivery = 1;
        }
        if ($request->get('delivery') == 'no') {
            $newTenant->delivery = 0;
        }
        $newTenant->status = 'close';
        $newTenant->user_id = $userId->id;
        $newTenant->save();

        return redirect()->route('tenant.index')->with('status', 'Tenant ' . $request->get('tenant_name') . ' Berhasil ditambahkan!');
    }
    public function edit(Tenant $tenant)
    {
        return view('tenant.edit', compact('tenant'));
    }
    public function update(Request $request, Tenant $tenant)
    {
        $request->validate(["tenant_name" => "required", "tenant_address" => "required", "phone_number" => "required", "type" => "required", "opening_hour" => "required", "closing_hour" => "required", "bank_name" => "required", "bank_account" => "required", "bank_holder" => "required", "delivery" => "required", "username" => "required"]);

        if ($request->get('password') != null) {
            if (strlen($request->get('password')) < 8) {
                return redirect()->back()->withErrors(['message' => 'Password Harus Terdiri dari Minimum 8 Karakter!']);
            }
            if ($request->get('conf_pass') == null) {
                return redirect()->back()->withErrors(['message' => 'Konfirmasi Password Tidak Boleh Kosong!']);
            }
            if ($request->get('conf_pass') != $request->get('password')) {
                return redirect()->back()->withErrors(['message' => 'Konfirmasi Password Tidak Sesuai!']);
            }
        }

        $tenant->name = $request->get('tenant_name');
        $tenant->address = $request->get('tenant_address');
        $tenant->phone_number = $request->get('phone_number');
        if ($request->get('type') == 'product') {
            $tenant->type = 'product';
        }
        if ($request->get('type') == 'servive') {
            $tenant->type = 'service';
        }
        $tenant->service_hour_start = $request->get('opening_hour');
        $tenant->service_hour_end = $request->get('closing_hour');
        $tenant->bank_name = $request->get('bank_name');
        $tenant->bank_account = $request->get('bank_account');
        $tenant->account_holder = $request->get('bank_holder');
        if ($request->get('delivery') == 'yes') {
            $tenant->delivery = 1;
        }
        if ($request->get('delivery') == 'no') {
            $tenant->delivery = 0;
        }
        $tenant->status = 'close';
        $tenant->save();

        $user = User::where('username', $tenant->user->username)->first();
        if ($request->get('username') != $user->username) {
            $user->username = $request->get('username');
            $user->save();
        }
        if ($request->get('password') != null) {
            $user->password = Hash::make($request->get('password'));
        }

        return redirect()->route('tenant.index')->with('status', 'Data tenant ' . $request->get('tenant_name') . ' Berhasil diperbarui!');
    }

    public function deactivate(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $tenant = Tenant::find($tenant_id);
        $tenant->active_status = 0;
        $tenant->save();

        return redirect()->route('tenant.index')->with('status', 'Tenant ' . $tenant->name . ' Berhasil dinonaktifkan!');
    }
    public function activate(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $tenant = Tenant::find($tenant_id);
        $tenant->active_status = 1;
        $tenant->save();

        return redirect()->route('tenant.index')->with('status', 'Tenant ' . $tenant->name . ' Berhasil diaktifkan kembali!');
    }

    // API
    // TenantAPI
    public function getTenantProfile(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenant = Tenant::select('id', 'name', 'address', 'phone_number', 'service_hour_start', 'service_hour_end', 'bank_name', 'bank_account', 'account_holder', 'delivery')->where('id', $tenant_id)->first();
            $arrResponse = ["status" => "success", "data" => $tenant];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }

        return $arrResponse;
    }

    public function getTenantStatus(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenant = Tenant::select('status')->where('id', $tenant_id)->first();
            $arrResponse = ["status" => "success", "tenant_status" => $tenant->status];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function changeTenantStatus(Request $request)
    {
        $tenant_id = $request->get('tenant_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenant = Tenant::select('id', 'status')->where('id', $tenant_id)->first();
            if ($tenant != null) {
                if ($tenant->status == 'open') {
                    $tenant->status = 'close';
                } else {
                    $tenant->status = 'open';
                }
                $tenant->save();
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
    public function rdtProductTenantList(Request $request)
    {
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenantsOpen = Tenant::select('id', 'name', 'address', 'service_hour_start', 'service_hour_end', 'delivery')->where('type', 'product')->where('active_status', 1)->whereRaw('service_hour_start <= time(now()) and service_hour_end >= time(now())')->where('status', 'open')->get();
            if (count($tenantsOpen) > 0) {
                foreach ($tenantsOpen as $to) {
                    $to->status = 'open';
                }
            }
            $tenantsClose = Tenant::select('id', 'name', 'address', 'service_hour_start', 'service_hour_end', 'delivery')->where('type', 'product')->where('active_status', 1)->whereRaw("((service_hour_start > time(now()) or service_hour_end < time(now())) or status ='close')")->get();
            if (count($tenantsClose) > 0) {
                foreach ($tenantsClose as $tc) {
                    $tc->status = 'close';
                }
            }
            $tenants = $tenantsOpen->merge($tenantsClose);
            if (count($tenants) > 0) {
                $arrResponse = ["status" => "success", "data" => $tenants];
            } else {
                $arrResponse = ["status" => "empty"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function rdtServiceTenantList(Request $request)
    {
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenantsOpen = Tenant::select('id', 'name', 'address', 'service_hour_start', 'service_hour_end', 'delivery')->where('type', 'service')->where('active_status', 1)->whereRaw('service_hour_start <= time(now()) and service_hour_end >= time(now())')->where('status', 'open')->get();
            if (count($tenantsOpen) > 0) {
                foreach ($tenantsOpen as $to) {
                    $to->status = 'open';
                }
            }
            $tenantsClose = Tenant::select('id', 'name', 'address', 'service_hour_start', 'service_hour_end', 'delivery')->where('type', 'service')->where('active_status', 1)->whereRaw("((service_hour_start > time(now()) or service_hour_end < time(now())) or status ='close')")->get();
            if (count($tenantsClose) > 0) {
                foreach ($tenantsClose as $tc) {
                    $tc->status = 'close';
                }
            }
            $tenants = $tenantsOpen->merge($tenantsClose);
            if (count($tenants) > 0) {
                $arrResponse = ["status" => "success", "data" => $tenants];
            } else {
                $arrResponse = ["status" => "empty"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
}
