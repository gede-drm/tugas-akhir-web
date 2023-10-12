<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        return view('tenant.index', compact('tenants'));
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
}
