<?php

namespace App\Http\Controllers;

use App\Models\Tower;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UnitController extends Controller
{
    public function index()
    {
        $activeUnits = Unit::where('active_status', 1)->orderBy('unit_no', 'asc')->get();
        $nonactiveUnits = Unit::where('active_status', 0)->orderBy('unit_no', 'asc')->get();
        return view('unit.index', compact('activeUnits', 'nonactiveUnits'));
    }
    public function add()
    {
        $towers = Tower::where('active_status', 1)->get();
        return view('unit.add', compact('towers'));
    }
    public function store(Request $request)
    {
        $request->validate(["tower" => "required", "unit_no" => "required|unique:units", "owner_name"=>"required", "holder_name" => "required", "holder_ph_number"=>"required", "password" => "required|min:8", "conf_pass" => "required|same:password"], ["conf_pass.same" => "Konfirmasi Password Tidak Sesuai!", "unit_no.unique" => "Nomor Unit Terkait Telah Terdaftar!"]);

        User::create([
            "username" => $request->get('unit_no'),
            "password" => Hash::make($request->get('password')),
            "role" => "resident"
        ]);

        $userId = User::select('id')->where('username', $request->get('unit_no'))->first();
        $newUnit = new Unit();
        $newUnit->tower_id = $request->get('tower');
        $newUnit->unit_no = $request->get('unit_no');
        $newUnit->owner_name = $request->get('owner_name');
        $newUnit->holder_name = $request->get('holder_name');
        $newUnit->holder_ph_number = $request->get('holder_ph_number');
        $newUnit->wma_preference = 3;
        $newUnit->user_id = $userId->id;
        $newUnit->save();

        return redirect()->route('unit.index')->with('status', 'Unit ' . $request->get('unit_no') . ' Berhasil ditambahkan!');
    }
    public function edit(Unit $unit)
    {
        return view('unit.edit', compact('unit'));
    }
    public function update(Request $request, Unit $unit)
    {
        $request->validate(["owner_name"=>"required","holder_name" => "required", "holder_ph_number"=>"required", "password" => "required|min:8", "conf_pass" => "required|same:password"], ["conf_pass.same" => "Konfirmasi Password Tidak Sesuai!"]);
        $user = User::find($unit->user_id);
        $user->password = Hash::make($request->get('password'));
        $unit->owner_name = $request->get('owner_name');
        $unit->holder_name = $request->get('holder_name');
        $unit->holder_ph_number = $request->get('holder_ph_number');
        $user->save();
        $unit->save();

        return redirect()->route('unit.index')->with('status', 'Data unit ' . $request->get('unit_no') . ' Berhasil diperbarui!');
    }
    public function deactivateUnit(Request $request){
        $unit_id = $request->get('unit_id');
        $unit = Unit::find($unit_id);
        $unit->active_status = 0;
        $unit->save();

        return redirect()->route('unit.index')->with('status', 'Unit ' . $unit->unit_no . ' Berhasil dinonaktifkan!');
    }
    public function activateUnit(Request $request){
        $unit_id = $request->get('unit_id');
        $unit = Unit::find($unit_id);
        $unit->active_status = 1;
        $unit->save();

        return redirect()->route('unit.index')->with('status', 'Unit ' . $unit->unit_no . ' Berhasil diaktifkan kembali!');
    }


    // Tower
    public function towerIndex()
    {
        $activeTowers = Tower::where('active_status', 1)->orderBy('name', 'asc')->get();;
        $nonactiveTowers = Tower::where('active_status', 0)->orderBy('name', 'asc')->get();;
        return view('tower.index', compact('activeTowers', 'nonactiveTowers'));
    }
    public function towerAdd()
    {
        return view('tower.add');
    }

    public function towerEdit(Tower $tower)
    {
        return view('tower.edit', compact('tower'));
    }

    public function towerStore(Request $request)
    {
        $request->validate(["tower" => "required"]);

        $newTower = new Tower();
        $newTower->name = $request->get('tower');
        $newTower->save();

        return redirect()->route('tower.index')->with('status', 'Tower ' . $request->get('tower') . ' Berhasil ditambahkan!');
    }

    public function towerUpdate(Request $request, Tower $tower)
    {
        $request->validate(["tower" => "required"]);

        $tower->name = $request->get('tower');
        $tower->save();

        return redirect()->route('tower.index')->with('status', 'Data tower ' . $request->get('tower') . ' Berhasil diperbarui!');
    }

    public function deactivateTower(Request $request){
        $tower_id = $request->get('tower_id');
        $tower = Tower::find($tower_id);
        $tower->active_status = 0;
        $tower->save();

        return redirect()->route('tower.index')->with('status', 'Tower ' . $tower->name . ' Berhasil dinonaktifkan!');
    }
    public function activateTower(Request $request){
        $tower_id = $request->get('tower_id');
        $tower = Tower::find($tower_id);
        $tower->active_status = 1;
        $tower->save();

        return redirect()->route('tower.index')->with('status', 'Tower ' . $tower->name . ' Berhasil diaktifkan kembali!');
    }


    // API
    public function getTower()
    {
        $towers = Tower::select('id', 'name')->get();
        return ["status" => "success", "data" => $towers];
    }
    public function getUnitNo()
    {
        $units = Unit::select('id', 'unit_no')->get();
        return ["status" => "success", "data" => $units];
    }
    public function getUnitNoByTower(Request $request)
    {
        $tower_id = $request->get('tower');
        $units = Unit::select('id', 'unit_no')->where('tower_id', $tower_id)->get();
        $arrResponse = [];
        if(count($units)>0){
            $arrResponse = ["status" => "success", "data" => $units];
        }
        else{
            $arrResponse = ["status" => "empty"];
        }
        return $arrResponse;
    }
}
