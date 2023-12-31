<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\SecurityOfficerCheckin;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // General
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
                    $shift = SecurityOfficerCheckin::whereRaw("(timestampdiff(second, now(), check_in) < 0 and timestampdiff(second, now(), check_out)>0)")->orderBy('check_in', 'desc')->where('security_officer_id', $userSecurity->security->id)->first();
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

    // API Tenant
    public function tenantLogin(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $userTenant = User::select('id', 'password', 'role')->where('username', $username)->first();
        $arrResponse = [];
        if ($userTenant != null) {
            if ($userTenant->role == 'tenant') {
                if ($userTenant->tenant->active_status == 1) {
                    if (Hash::check($password, $userTenant->password)) {
                        $token = Helper::generateToken();
                        $userTenant->api_token = $token;
                        $userTenant->save();

                        $fcm_token = $userTenant->fcm_token;
                        if($fcm_token == null){
                            $fcm_token = "";
                        }

                        $arrResponse = ['status' => 'success', 'data' => ['tenant_id' => $userTenant->tenant->id, 'tenant_name' => $userTenant->tenant->name, 'tenant_type' => $userTenant->tenant->type, 'token' => $token, 'fcm_token'=>$fcm_token]];
                    } else {
                        $arrResponse = ['status' => 'failed'];
                    }
                } else {
                    $arrResponse = ['status' => 'notactive'];
                }
            } else {
                $arrResponse = ['status' => 'failed'];
            }
        } else {
            $arrResponse = ['status' => 'failed'];
        }
        return $arrResponse;
    }
    public function tenRegisterFCMToken(Request $request){
        $tenant_id = $request->get('tenant_id');
        $fcm_token = $request->get('fcm_token');
        $token = $request->get('token');

        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $tenantUserId = Tenant::select('user_id')->where('id', $tenant_id)->first();
            $user = User::where('id', $tenantUserId->user_id)->first();
            $user->fcm_token = $fcm_token;
            $user->save();

            $arrResponse = ["status" => "success"];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }

    // API Resident
    public function residentLogin(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $userResident = User::select('id', 'password', 'role', 'fcm_token')->where('username', $username)->first();
        $arrResponse = [];
        if ($userResident != null) {
            if ($userResident->role == 'resident') {
                if ($userResident->unit->active_status == 1) {
                    if (Hash::check($password, $userResident->password)) {
                        $token = Helper::generateToken();
                        $userResident->api_token = $token;
                        $userResident->save();

                        $fcm_token = $userResident->fcm_token;
                        if($fcm_token == null){
                            $fcm_token = "";
                        }
                        $arrResponse = ['status' => 'success', 'data' => ['resident_id' => $userResident->unit->id, 'unit_no' => $userResident->unit->unit_no, 'holder_name' => $userResident->unit->holder_name, 'token' => $token, 'fcm_token'=>$fcm_token]];
                    } else {
                        $arrResponse = ['status' => 'wrongpass'];
                    }
                } else {
                    $arrResponse = ['status' => 'notactive'];
                }
            } else {
                $arrResponse = ['status' => 'failed'];
            }
        } else {
            $arrResponse = ['status' => 'failed'];
        }
        return $arrResponse;
    }

    public function rdtRegisterFCMToken(Request $request){
        $unit_id = $request->get('unit_id');
        $fcm_token = $request->get('fcm_token');
        $token = $request->get('token');

        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $unitUserId = Unit::select('user_id')->where('id', $unit_id)->first();
            $user = User::where('id', $unitUserId->user_id)->first();
            $user->fcm_token = $fcm_token;
            $user->save();

            $arrResponse = ["status" => "success"];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
}
