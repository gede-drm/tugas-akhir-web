<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\SecurityOfficerCheckin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // API Security
    public function securityLogin(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $userSecurity = User::select('id', 'password', 'role')->where('username', $username)->first();
        $arrResponse = [];
        if ($userSecurity != null) {
            if ($userSecurity->role == 'security') {
                if (Hash::check($password, $userSecurity->password)) {
                    $shift = SecurityOfficerCheckin::whereRaw("(timestampdiff(minute, now(), check_in) < 0 and timestampdiff(minute, now(), check_out)>0)")->orderBy('check_in', 'desc')->where('security_officer_id', $userSecurity->security->id)->first();
                    if ($shift != null) {
                        $token = Helper::generateToken();
                        $userSecurity->api_token = $token;
                        $userSecurity->save();
                        $arrResponse = ['status' => 'success', 'data' => ['security_id' => $userSecurity->security->id, 'security_name' => $userSecurity->security->name, 'username' => $username, 'tower_id' => $shift->tower->id, 'tower_name' => $shift->tower->name, 'token' => $token]];
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

    public function checkshift(Request $request)
    {
        $securityId = $request->get('security');
        $tower = $request->get('tower');
        $token = $request->get('token');

        $arrResponse = [];
        $tokenValidation = Helper::validateToken($token);
        if ($tokenValidation == true) {
            $status = Helper::checkSecurityShift($securityId, $tower);
            $arrResponse = ["status" => $status];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    public function clearToken(Request $request)
    {
        $username = $request->get('username');
        $token = $request->get('token');

        $arrResponse = [];
        $tokenValidation = Helper::validateToken($token);
        if ($tokenValidation == true) {
            $user = User::select('id', 'api_token')->where('username', $username)->where('api_token', $token)->first();
            $user->api_token = null;
            $user->save();

            $arrResponse = ["status" => "success"];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    // API Tenant
    public function tenantLogin(Request $request){
        $username = $request->get('username');
        $password = $request->get('password');

        $userTenant = User::select('id', 'password', 'role')->where('username', $username)->first();
        $arrResponse = [];
        if ($userTenant != null) {
            if ($userTenant->role == 'tenant') {
                if (Hash::check($password, $userTenant->password)) {
                        $token = Helper::generateToken();
                        $userTenant->api_token = $token;
                        $userTenant->save();
                        $arrResponse = ['status' => 'success', 'data' => ['tenant_id' => $userTenant->tenant->id, 'tenant_name' => $userTenant->tenant->name, 'tenant_type'=>$userTenant->tenant->type, 'token' => $token]];
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
}
