<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Svg\Tag\Rect;

class PermissionController extends Controller
{
    public function index()
    {
        $pendingPermissions = Permission::whereNull('status')->orderBy('proposal_date', 'desc')->get();
        $historyPermissions = Permission::whereNotNull('status')->orderBy('proposal_date', 'desc')->get();

        return view('permission.index', compact('pendingPermissions', 'historyPermissions'));
    }

    public function detail(Permission $permission)
    {
        return view('permission.detail', compact('permission'));
    }

    public function accept(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $permisssionId = $request->get('permission_id');
        $permission = Permission::find($permisssionId);
        $permission->approval_date = $date;
        $permission->status = 'accept';
        $permission->management_id = Auth::user()->id;

        $strCode = $permission->id . $permission->serviceTransaction->unit->unit_no . $date . 'accept';
        $verificationCode = hash('sha256', $strCode);
        $qrFileName = 'permission-qr-' . $permission->id . md5($strCode) . '.png';
        $qrFileDirectory = '../public/permissions/qr-code/' . $qrFileName;
        QrCode::size(500)->margin(2)->color(0,33,71)->format('png')->generate($verificationCode, $qrFileDirectory);

        $permission->verification_code = $verificationCode;
        $permission->qr_url = $qrFileName;

        $tenant = $permission->serviceTransaction->services[0]->tenant;
        $letterFileName = 'permission-letter-' . $permission->id . md5($strCode) . '.pdf';
        $letterFileDirectory = '../public/permissions/approval-letter/' . $letterFileName;
        $data = ['permission' => $permission, 'tenant' => $tenant, 'date' => $date];
        Pdf::loadView('permission.letter.accept', $data)->setPaper('a4', 'potrait')->save($letterFileDirectory);

        $permission->approval_letter_url = $letterFileName;
        $permission->save();

        return redirect()->route('permission.detail', $permission->id)->with('status', 'Persetujuan Perizinan Berhasil dilakukan!');
    }

    public function reject(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $permisssionId = $request->get('permission_id');
        $permission = Permission::find($permisssionId);
        $permission->approval_date = $date;
        $permission->status = 'reject';
        $permission->management_id = Auth::user()->id;

        $rejectionReason = $request->get('reject_reason');
        $strCode = $permission->id . $permission->serviceTransaction->unit->unit_no . $date . 'reject';
        $tenant = $permission->serviceTransaction->services[0]->tenant;
        $letterFileName = 'permission-letter-' . $permission->id . md5($strCode) . '.pdf';
        $letterFileDirectory = '../public/permissions/approval-letter/' . $letterFileName;
        $data = ['permission' => $permission, 'tenant' => $tenant, 'date' => $date, 'rejectionReason' => $rejectionReason];
        Pdf::loadView('permission.letter.reject', $data)->setPaper('a4', 'potrait')->save($letterFileDirectory);

        $permission->approval_letter_url = $letterFileName;
        $permission->save();

        return redirect()->route('permission.detail', $permission->id)->with('status', 'Persetujuan Perizinan Berhasil dilakukan!');
    }

    public function downloadApprovalLetter(Request $request)
    {
        $permisssionId = $request->get('permission_id');
        $permission = Permission::find($permisssionId);
        $fileURL = $permission->approval_letter_url;

        return response()->download(public_path('/permissions/approval-letter/'.$fileURL), 'perizinan-' . $permission->status . '_' . $permission->serviceTransaction->unit->unit_no . '_' . $permission->id . '.pdf');
    }

    // API
    public function secPermissionList(Request $request){

    }
    public function secPermissionDetail(Request $request){
        
    }
    public function secPermissionScan(Request $request){
        
    }
    public function secPermissionSaveScan(Request $request){
        
    }
}
