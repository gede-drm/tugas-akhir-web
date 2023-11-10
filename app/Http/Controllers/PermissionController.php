<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Permission;
use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

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
        QrCode::size(500)->margin(2)->color(0, 33, 71)->format('png')->generate($verificationCode, $qrFileDirectory);

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

        return response()->download(public_path('/permissions/approval-letter/' . $fileURL), 'perizinan-' . $permission->status . '_' . $permission->serviceTransaction->unit->unit_no . '_' . $permission->id . '.pdf');
    }

    // API
    public function secPermissionList(Request $request)
    {
        $tower = $request->get('tower');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $permissions = Permission::where('status', 'accept')->whereRaw('(date(start_date) <=\'' . date('Y-m-d') . '\') and (date(end_date) >=\'' . date('Y-m-d') . '\')')->get();
            if (count($permissions) > 0) {
                foreach ($permissions as $key => $permission) {
                    if ($permission->serviceTransaction->unit->tower_id != $tower) {
                        $permissions->forget($key);
                    } else {
                        $permitsTodayCount = Permit::where('permission_id', $permission->id)->whereRaw('date(date) = \'' . date('Y-m-d') . '\'')->count();
                        if ($permitsTodayCount > 0) {
                            $permission['unit_no'] = $permission->serviceTransaction->unit->unit_no;
                            $permission['tenant'] = $permission->serviceTransaction->services[0]->tenant->name;
                            $permission['workPermitsCount'] = $permitsTodayCount;
                        } else {
                            $permissions->forget($key);
                        }
                    }
                }
                if (count($permissions) > 0) {
                    $arrResponse = ['status' => 'success', 'data' => $permissions];
                } else {
                    $arrResponse = ['status' => 'empty'];
                }
            } else {
                $arrResponse = ['status' => 'empty'];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function secPermissionDetail(Request $request)
    {
        $idPermission = $request->get('permission_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $permission = Permission::select('id', 'description', 'start_date', 'end_date', 'number_of_worker', 'service_transaction_id')->where('id', $idPermission)->first();
            if ($permission != null) {
                $permission['unit_no'] = $permission->serviceTransaction->unit->unit_no;
                $permission['tenant'] = $permission->serviceTransaction->services[0]->tenant->name;
                $permits = Permit::where('permission_id', $permission->id)->whereRaw('date(date) = \'' . date('Y-m-d') . '\'')->get();
                if (count($permits) > 0) {
                    $permission['officer'] = $permits[0]->security->name . ' (' . $permits[0]->security->employeeid . ')';
                    foreach ($permits as $permit) {
                        $permit['worker_name'] = $permit->worker->worker_name;
                        $permit['idcard_number'] = $permit->worker->idcard_number;
                    }
                    $permission->makeHidden('permits');
                    $permission->makeHidden('serviceTransaction');

                    $permission['permits'] = $permits;
                    
                    $arrResponse = ['status' => 'success', 'data' => $permission];
                } else {
                    $arrResponse = ["status" => "nopermit"];
                }
            } else {
                $arrResponse = ["status" => "notfound"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function secPermissionScan(Request $request)
    {
        $verification_code = $request->get('code');
        $officer_id = $request->get('officer');
        $tower_id = $request->get('tower');

        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $statusSecurity = Helper::checkSecurityShift($officer_id, $tower_id);
            if ($statusSecurity == 'exist') {
                $permission = Permission::select('id', 'service_transaction_id')->where('status', 'accept')->whereRaw('(date(start_date) <=\'' . date('Y-m-d') . '\') and (date(end_date) >=\'' . date('Y-m-d') . '\')')->where('verification_code', $verification_code)->first();
                if ($permission != null) {
                    $countPermits = Permit::where('permission_id', $permission->id)->whereRaw('date(date) = \'' . date('Y-m-d') . '\'')->count();
                    if ($countPermits == 0) {
                        if ($permission->serviceTransaction->unit->tower_id != $tower_id) {
                            $arrResponse = ["status" => "othertower"];
                        } else {
                            $arrResponse = ["status" => "success", "id" => $permission->id];
                        }
                    } else {
                        $arrResponse = ["status" => "permitted"];
                    }
                } else {
                    $arrResponse = ["status" => "notfound"];
                }
            } else {
                $arrResponse = ['status' => 'securityprob', 'securitystatus' => $statusSecurity];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }

        return $arrResponse;
    }
    public function secPermissionWorkersDetail(Request $request)
    {
        $idPermission = $request->get('permission_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        $arrResponse = [];
        if ($tokenValidation == true) {
            $permission = Permission::select('id', 'description', 'start_date', 'end_date', 'number_of_worker', 'service_transaction_id')->where('id', $idPermission)->with('workers')->first();
            if ($permission != null) {
                $permission['unit_no'] = $permission->serviceTransaction->unit->unit_no;
                $permission['tenant'] = $permission->serviceTransaction->services[0]->tenant->name;
                $permission->makeHidden('serviceTransaction');

                $arrResponse = ['status' => 'success', 'data' => $permission];
            } else {
                $arrResponse = ["status" => "notfound"];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }

        return response()->json($arrResponse);
    }
    public function secPermissionAddPermits(Request $request)
    {
    }
}
