<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(){
        $tenants = Tenant::all();
        return view('tenant.index', compact('tenants'));
    }
    public function add(){
        return view('tenant.add');
    }
}
