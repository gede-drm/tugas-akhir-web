<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Helper extends Model
{
    use HasFactory;
    public static function generateToken()
    {
        $token = Str::random(100);
        return $token;
    }
    public static function validateToken($token)
    {
        if ($token != "") {
            $validate = User::select('id')->where('api_token', $token)->count();
            if ($validate != 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public static function checkSecurityShift($securityId, $towerId)
    {
        $userSecurity = User::whereRelation('security', 'id', $securityId)->first();
        if ($userSecurity != null) {
            $shift = SecurityOfficerCheckin::whereRaw("(timestampdiff(second, now(), check_in) < 0 and timestampdiff(second, now(), check_out)>0)")->whereRelation('tower', 'tower_id', $towerId)->orderBy('check_in', 'desc')->where('security_officer_id', $userSecurity->security->id)->first();
            $status = "";

            if ($shift != null) {
                $status = 'exist';
            } else {
                $otherShift = SecurityOfficerCheckin::whereRaw("(timestampdiff(second, now(), check_in) < 0 and timestampdiff(second, now(), check_out)>0)")->orderBy('check_in', 'desc')->where('security_officer_id', $userSecurity->security->id)->first();
                if ($otherShift != null) {
                    $status = 'othershift';
                    $userSecurity->api_token = null;
                    $userSecurity->save();
                } else {
                    $status = 'noshift';
                    $userSecurity->api_token = null;
                    $userSecurity->save();
                }
            }
        } else {
            $status = 'notfound';
        }
        return $status;
    }

    public static function clearFCMToken($id)
    {
        $user = User::find($id);
        $user->fcm_token = null;
        $user->save();
    }
    public static function monthToRoman($month){
        $roman = ['I', 'II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        $monthRoman = $roman[$month-1];
        return $monthRoman;
    }
    public static $base_url = "https://gede-darma.my.id/";
}
