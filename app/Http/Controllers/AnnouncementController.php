<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Helper;
use App\Models\User;
use App\Notifications\SendNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('date', 'desc')->get();
        return view('announcement.index', compact('announcements'));
    }
    public function store(Request $request)
    {
        $request->validate(["title" => "required", "description" => "required"]);

        $announcement = new Announcement();
        $announcement->date = date("Y-m-d H:i:s");
        $announcement->title = $request->get('title');
        $announcement->description = $request->get('description');
        $announcement->management_id = Auth::user()->id;
        $announcement->save();

        $userResidents = User::select('id', 'fcm_token')->whereNotNull('fcm_token')->where('role', 'resident')->get();
        foreach ($userResidents as $user) {
            $title = "Pemberitahuan Baru dari Manajemen";
            try {
                $delay = now()->addSeconds(20);
                $user->notify((new SendNotification(["title" => $title, "body" => $announcement->title]))->delay($delay));
            } catch (Exception $e) {
                Helper::clearFCMToken($user->id);
            }
        }

        return redirect()->route('announcement.index')->with('status', 'Pemberitahuan Baru Berhasil ditambahkan!');
    }

    // API
    // Residents' App API
    public function rdtGetLatestAnnouncement(Request $request)
    {
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $announcement = Announcement::select('date', 'title', 'description')->orderBy('date', 'desc')->first();
            if ($announcement != null) {
                $announcement->date = date("d-m-Y H:i", strtotime($announcement->date));
                $arrResponse = ["status" => "success", "data" => $announcement];
            } else {
                $arrResponse = ["status" => "empty"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
}
