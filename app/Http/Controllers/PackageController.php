<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackageController extends Controller
{
    // API
    public function secPackagePendingList(Request $request){
        $tower = $request->get('tower');
    }
    public function secPackageDetail(Request $request){

    }
    public function secPackageEntry(Request $request){

    }
    public function secPackageCollection(Request $request){

    }
}
