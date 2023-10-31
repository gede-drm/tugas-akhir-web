<?php

namespace App\Http\Controllers;

use App\Models\SecurityOfficerCheckin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // API
    public function securityLogin(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $userSecurity = User::select('id', 'password', 'role')->where('username', $username)->first();
        $arrResponse = [];
        if ($userSecurity != null) {
            if ($userSecurity->role == 'security') {
                if (Hash::check($password, $userSecurity->password)) {
                    $shift = SecurityOfficerCheckin::where('check_in', 'like', '%' . date('Y-m-d') . '%')->whereNull('check_out')->orderBy('check_in', 'desc')->where('security_officer_id', $userSecurity->security->id)->first();
                    if ($shift != null) {
                        $arrResponse = ['status' => 'success', 'data' => ['security_id' => $userSecurity->security->id, 'security_name' => $userSecurity->security->name, 'username' => $username, 'tower_id' => $shift->tower->id, 'tower_name' => $shift->tower->name]];
                    } else {
                        $arrResponse = ['status' => 'noshift'];
                    }
                } else {
                    $arrResponse = ['status' => 'failed'];
                }
            } else {
                $arrResponse = ['status' => 'failed'];
            }
        } else {
            $arrResponse = ['status' => 'failed'];
        }
        return $arrResponse;
    }

    public function checkshift(Request $request){
        $username = $request->get('username');
        $userSecurity = User::select('id')->where('username', $username)->first();
        $shift = SecurityOfficerCheckin::where('check_in', 'like', '%' . date('Y-m-d') . '%')->whereNull('check_out')->orderBy('check_in', 'desc')->where('security_officer_id', $userSecurity->security->id)->first();
        if ($shift != null) {
            $arrResponse = ['status' => 'exist'];
        } else {
            $arrResponse = ['status' => 'noshift'];
        }
        return $arrResponse;
    }
}
