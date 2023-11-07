<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index(){
        $settingSecurity = SystemSetting::where('configuration_name', 'security_shift')->first();
        return view('setting.index', compact('settingSecurity'));
    }

    public function updateSetting(Request $request){
        $request->validate(['security_shift_duration'=>'required|numeric']);
        $value = $request->get('security_shift_duration');
        
        $settingSecurity = SystemSetting::where('configuration_name', 'security_shift')->first();
        $settingSecurity->value = $value;
        $settingSecurity->save();
        
        return redirect()->route('setting.index')->with('status', 'Setting berhasil diubah!');
    }
}
