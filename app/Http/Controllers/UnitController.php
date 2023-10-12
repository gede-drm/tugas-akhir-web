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
        $units = Unit::all();
        return view('unit.index', compact('units'));
    }
    public function add()
    {
        $towers = Tower::all();
        return view('unit.add', compact('towers'));
    }
    public function store(Request $request)
    {
        $request->validate(["tower" => "required", "unit_no" => "required|unique:units", "holder_name" => "required", "password" => "required|min:8", "conf_pass" => "required|same:password"], ["conf_pass.same" => "Konfirmasi Password Tidak Sesuai!", "unit_no.unique" => "Nomor Unit Terkait Telah Terdaftar!"]);

        User::create([
            "username" => $request->get('unit_no'),
            "password" => Hash::make($request->get('password')),
            "role" => "resident"
        ]);

        $userId = User::select('id')->where('username', $request->get('unit_no'))->first();
        $newUnit = new Unit();
        $newUnit->tower_id = $request->get('tower');
        $newUnit->unit_no = $request->get('unit_no');
        $newUnit->holder_name = $request->get('holder_name');
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
        $request->validate(["holder_name" => "required", "password" => "required|min:8", "conf_pass" => "required|same:password"], ["conf_pass.same" => "Konfirmasi Password Tidak Sesuai!"]);
        $user = User::find($unit->user_id);
        $user->password = Hash::make($request->get('password'));
        $unit->holder_name = $request->get('holder_name');
        $user->save();
        $unit->save();

        return redirect()->route('unit.index')->with('status', 'Data unit ' . $request->get('unit_no') . ' Berhasil diperbarui!');
    }


    // Tower
    public function towerIndex()
    {
        $towers = Tower::all();
        return view('tower.index', compact('towers'));
    }
    public function towerAdd()
    {
        return view('tower.add');
    }

    public function towerEdit(Tower $tower)
    {
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
    }


    // API
    public function testAPI(Request $request)
    {
        $request->validate(['id' => 'required']);
        $id = $request->get('id');
        return ["status" => "OK", "data" => "Hello, This is Laravel API!\nID: " . $id];
    }
}
