<?php

namespace App\Http\Controllers;

use App\Models\SecurityOfficer;
use App\Models\SecurityOfficerCheckin;
use App\Models\Tower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecurityOfficerController extends Controller
{
    public function index(){
        $activeSecurities = SecurityOfficer::where('active_status', 1)->orderBy('name', 'asc')->get();
        $nonactiveSecurities = SecurityOfficer::where('active_status', 0)->orderBy('name', 'asc')->get();
        return view('security.index', compact('activeSecurities', 'nonactiveSecurities'));
    }
    public function add(){
        return view('security.add');
    }
    public function store(Request $request){
        $request->validate(["security_id"=>"required", "security_name"=>"required", "username" => "required|unique:users,username", "password" => "required|min:8", "conf_pass" => "required|same:password"], ["conf_pass.same" => "Konfirmasi Password Tidak Sesuai!"]);

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

    public function deactivate(Request $request){
        $satpam_id = $request->get('satpam_id');
        $security = SecurityOfficer::find($satpam_id);
        $security->active_status = 0;
        $security->save();

        return redirect()->route('security.index')->with('status', 'Satpam ' . $security->name . ' Berhasil dinonaktifkan!');
    }
    public function activate(Request $request){
        $satpam_id = $request->get('satpam_id');
        $security = SecurityOfficer::find($satpam_id);
        $security->active_status = 1;
        $security->save();

        return redirect()->route('security.index')->with('status', 'Satpam ' . $security->name . ' Berhasil diaktifkan kembali!');
    }

    public function checkin(){
        $securities = SecurityOfficer::all();
        foreach($securities as $security){
            $checkin = SecurityOfficerCheckin::where('check_in', 'like', '%'. date('Y-m-d').'%')->orderBy('check_in', 'desc')->where('security_officer_id', $security->id)->first();
            $security->check = $checkin;
        }
        return view('security.checkin', compact('securities'));
    }

    public function modalCheckin(Request $request){
        $satpam_id = $request->get('satpam_id');
        $security = SecurityOfficer::find($satpam_id);
        $towers = Tower::where('active_status', 1)->orderBy('name', 'asc')->get();

        return response()->json(array('data' => view('security.modalcheckin', compact('security', 'towers'))->render()), 200);
    }

    public function storeCheckin(Request $request){
        $request->validate(['tower'=>'required', 'satpam_id'=>'required']);
        $tower_id = $request->get('tower');
        $security_id = $request->get('satpam_id');
        $security_name = SecurityOfficer::select('name')->where('id', $security_id)->first()->name;

        $checkin = new SecurityOfficerCheckin();
        $checkin->check_in = date('Y-m-d H:i:s');
        $checkin->management_checkin_id = Auth::user()->id;
        $checkin->security_officer_id = $security_id;
        $checkin->tower_id = $tower_id;
        $checkin->save();

        return redirect()->route('security.checkin')->with('status', 'Checkin satpam ' . $security_name . ' Berhasil dilakukan!');
    }

    public function storeCheckout(Request $request){
        $request->validate(['satpam_id'=>'required']);
        $security_id = $request->get('satpam_id');
        $security_name = SecurityOfficer::select('name')->where('id', $security_id)->first()->name;
        $checkin = SecurityOfficerCheckin::where('check_in', 'like', '%'. date('Y-m-d').'%')->whereNull('check_out')->where('security_officer_id', $security_id)->first();
        $checkin->check_out =  date('Y-m-d H:i:s');
        $checkin->management_checkout_id = Auth::user()->id;
        $checkin->save();

        return redirect()->route('security.checkin')->with('status', 'Checkout satpam ' . $security_name . ' Berhasil dilakukan!');
    }

    public function checkinHistory(){
        $history = SecurityOfficerCheckin::orderBy('check_in', 'desc')->get();
        $history->sortByDesc('tower.name');

        return view('security.historycheckin', compact('history'));
    }
}
