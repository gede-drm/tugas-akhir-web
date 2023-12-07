<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index(){
        $announcements = Announcement::orderBy('date', 'desc')->get();
        return view('announcement.index', compact('announcements'));
    }
    public function store(Request $request){
        $request->validate(["title"=>"required", "description"=>"required"]);

        $announcement = new Announcement();
        $announcement->date = date("Y-m-d H:i:s");
        $announcement->title = $request->get('title');
        $announcement->description = $request->get('description');
        $announcement->management_id = Auth::user()->id;
        $announcement->save();

        // KURANG WMA
        
        return redirect()->route('announcement.index')->with('status', 'Pemberitahuan Baru Berhasil ditambahkan!');
    }
}
