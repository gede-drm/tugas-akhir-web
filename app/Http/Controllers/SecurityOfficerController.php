<?php

namespace App\Http\Controllers;

use App\Models\SecurityOfficer;
use App\Models\SecurityOfficerCheckin;
use App\Models\SystemSetting;
use App\Models\Tower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecurityOfficerController extends Controller
{
    public function index()
    {
        $activeSecurities = SecurityOfficer::where('active_status', 1)->orderBy('name', 'asc')->get();
        $nonactiveSecurities = SecurityOfficer::where('active_status', 0)->orderBy('name', 'asc')->get();
        return view('security.index', compact('activeSecurities', 'nonactiveSecurities'));
    }
    public function add()
    {
        return view('security.add');
    }
    public function store(Request $request)
    {
        $request->validate(["security_id" => "required", "security_name" => "required", "username" => "required|unique:users,username", "password" => "required|min:8", "conf_pass" => "required|same:password"], ["conf_pass.same" => "Konfirmasi Password Tidak Sesuai!"]);

        User::create([
            "username" => $request->get('username'),
            "password" => Hash::make($request->get('password')),
            "role" => "security"
        ]);

        $userId = User::select('id')->where('username', $request->get('username'))->first();
        $newSecurity = new SecurityOfficer();
        $newSecurity->employeeid = $request->get('security_id');
        $newSecurity->name = $request->get('security_name');
        $newSecurity->user_id = $userId->id;
        $newSecurity->save();

        return redirect()->route('security.index')->with('status', 'Satpam ' . $request->get('security_name') . ' Berhasil ditambahkan!');
    }

    public function deactivate(Request $request)
    {
        $satpam_id = $request->get('satpam_id');
        $security = SecurityOfficer::find($satpam_id);
        $security->active_status = 0;
        $security->save();

        return redirect()->route('security.index')->with('status', 'Satpam ' . $security->name . ' Berhasil dinonaktifkan!');
    }
    public function activate(Request $request)
    {
        $satpam_id = $request->get('satpam_id');
        $security = SecurityOfficer::find($satpam_id);
        $security->active_status = 1;
        $security->save();

        return redirect()->route('security.index')->with('status', 'Satpam ' . $security->name . ' Berhasil diaktifkan kembali!');
    }

    public function checkin()
    {
        $securities = SecurityOfficer::where('active_status', 1)->get();
        foreach ($securities as $security) {
            $checkin = SecurityOfficerCheckin::whereRaw("(check_in like '%".date('Y-m-d')."%' or timestampdiff(minute, now(), check_out)>0)")->where("security_officer_id", $security->id)->orderBy('check_in', 'desc')->first();
            $security->check = $checkin;
        }
        return view('security.checkin', compact('securities'));
    }

    public function modalCheckin(Request $request)
    {
        $satpam_id = $request->get('satpam_id');
        $security = SecurityOfficer::find($satpam_id);
        $towers = Tower::where('active_status', 1)->orderBy('name', 'asc')->get();

        return response()->json(array('data' => view('security.modalcheckin', compact('security', 'towers'))->render()), 200);
    }

    public function storeCheckin(Request $request)
    {
        $request->validate(['tower' => 'required', 'satpam_id' => 'required']);
        $tower_id = $request->get('tower');
        $security_id = $request->get('satpam_id');
        $security_name = SecurityOfficer::select('name')->where('id', $security_id)->first()->name;

        $date = date('Y-m-d H:i');
        $shift_duration = SystemSetting::select('value')->where('configuration_name', 'security_shift')->first();
        $checkin = new SecurityOfficerCheckin();
        $checkin->check_in = $date;
        $checkin->check_out = date('Y-m-d H:i', (strtotime($date."+".$shift_duration->value." hours")));
        $checkin->management_checkin_id = Auth::user()->id;
        $checkin->security_officer_id = $security_id;
        $checkin->tower_id = $tower_id;
        $checkin->save();

        return redirect()->route('security.checkin')->with('status', 'Check In Satpam ' . $security_name . ' Berhasil dilakukan!');
    }

    public function storeCheckout(Request $request)
    {
        $request->validate(['satpam_id' => 'required']);
        $security_id = $request->get('satpam_id');
        $security = SecurityOfficer::select('name', 'user_id')->where('id', $security_id)->first();
        $checkin = SecurityOfficerCheckin::where('security_officer_id', $security_id)->orderBy('check_in', 'desc')->first();
        $checkin->check_out =  date('Y-m-d H:i:s');
        $checkin->management_checkout_id = Auth::user()->id;
        $checkin->save();

        $userSecurity = User::select('id', 'api_token')->where('id', $security->id)->first();
        $userSecurity->api_token = null;
        $userSecurity->save();

        return redirect()->route('security.checkin')->with('status', 'Check Out Satpam ' . $security->name . ' Berhasil dilakukan!');
    }

    public function checkinHistory()
    {
        $history = SecurityOfficerCheckin::orderBy('check_in', 'desc')->get();
        $history->sortByDesc('tower.name');
        $start_date = null;
        $end_date = null;
        $security_name = null;

        return view('security.historycheckin', compact('history', 'start_date', 'end_date', 'security_name'));
    }

    public function checkinHistoryFilter(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $security_name = $request->get('security_name');

        if ($start_date != null && $end_date == null) {
            if ($security_name != null) {
                $history = SecurityOfficerCheckin::where('check_in', '>=', $start_date)->orderBy('check_in', 'desc')->get();
                $history = $history->where('security.name', $security_name);
                $history->sortByDesc('tower.name');
            } else {
                $history = SecurityOfficerCheckin::where('check_in', '>=', $start_date)->orderBy('check_in', 'desc')->get();
                $history->sortByDesc('tower.name');
            }
        } else if ($start_date == null && $end_date != null) {
            if ($security_name != null) {
                $history = SecurityOfficerCheckin::whereRaw('((date(check_out)<="' . $end_date . '") or (date(check_out) is null))')->orderBy('check_in', 'desc')->get();
                $history = $history->where('security.name', $security_name);
                $history->sortByDesc('tower.name');
            } else {
                $history = SecurityOfficerCheckin::whereRaw('((date(check_out)<="'. $end_date . '") or (date(check_out) is null))')->orderBy('check_in', 'desc')->get();
                $history->sortByDesc('tower.name');
            }
        } else if ($start_date == null && $end_date == null) {
            if ($security_name != null) {
                $history = SecurityOfficerCheckin::orderBy('check_in', 'desc')->get();
                $history = $history->where('security.name', $security_name);
                $history->sortByDesc('tower.name');
            } else {
                $history = SecurityOfficerCheckin::orderBy('check_in', 'desc')->get();
                $history->sortByDesc('tower.name');
            }
        } else {
            if ($security_name != null) {
                $history = SecurityOfficerCheckin::where('check_in', '>=', $start_date)->whereRaw('((date(check_out) <="'. $end_date . '") or (date(check_out) is null))')->orderBy('check_in', 'desc')->get();
                $history = $history->where('security.name', $security_name);
                $history->sortByDesc('tower.name');
            } else {
                $history = SecurityOfficerCheckin::where('check_in', '>=', $start_date)->whereRaw('((date(check_out) <="'. $end_date . '") or (date(check_out) is null))')->orderBy('check_in', 'desc')->get();
                $history->sortByDesc('tower.name');
            }
        }

        return view('security.historycheckin', compact('history', 'start_date', 'end_date', 'security_name'));
    }
}
