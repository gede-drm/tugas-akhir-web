<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\IncomingPackage;
use App\Models\SecurityOfficer;
use App\Models\Unit;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PackageController extends Controller
{
    // Web
    public function index()
    {
        $incomingPkgs = IncomingPackage::select('id', 'receive_date', 'description', 'photo_url', 'pickup_date', 'unit_id', 'receiving_security_officer_id', 'pickup_security_officer_id')->orderBy('receive_date', 'desc')->get();
        return view('package.index', compact('incomingPkgs'));
    }

    public function modalPhoto(Request $request)
    {
        $package_id = $request->get('package_id');
        $package = IncomingPackage::select('id', 'receive_date', 'description', 'photo_url', 'pickup_date', 'unit_id', 'receiving_security_officer_id', 'pickup_security_officer_id')->where('id', $package_id)->first();

        return response()->json(array('data' => view('package.modalphoto', compact('package'))->render()), 200);
    }

    // API
    public function secPackagePendingList(Request $request)
    {
        $tower = $request->get('tower');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);
        if ($tokenValidation == true) {
            $packages = IncomingPackage::select('id', 'receive_date', 'photo_url', 'unit_id')->whereRelation('unit', 'tower_id', $tower)->whereNull('pickup_date')->orderBy('receive_date', 'desc')->get();
            $arrResponse = [];
            if (count($packages) > 0) {
                foreach ($packages as $package) {
                    $package['unit_no'] = $package->unit->unit_no;
                    $package->photo_url = "https://gede-darma.my.id/packages/photos/" . $package->photo_url;
                }
                $arrResponse = ['status' => 'success', 'data' => $packages];
            } else {
                $arrResponse = ['status' => 'empty'];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
    public function secPackageDetail(Request $request)
    {
        $idPackage = $request->get('package_id');
        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        if ($tokenValidation == true) {
            $package = IncomingPackage::select('id', 'receive_date', 'description', 'photo_url', 'unit_id')->where('id', $idPackage)->first();
            $package['unit_no'] = $package->unit->unit_no;
            $package->photo_url = "https://gede-darma.my.id/packages/photos/" . $package->photo_url;
            $arrResponse = ['status' => 'success', 'data' => $package];
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }

        return $arrResponse;
    }
    public function secPackageEntry(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $description = $request->get('description');
        $officer_id = $request->get('officer');
        $tower_id = $request->get('tower');
        $unit_no = $request->get('unit');
        $base64Image = $request->get('image');

        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        if ($tokenValidation == true) {
            $statusSecurity = Helper::checkSecurityShift($officer_id, $tower_id);
            if ($statusSecurity == 'exist') {
                $unitId = Unit::select('id')->where('unit_no', $unit_no)->where('tower_id', $tower_id)->first();

                if ($unitId != null) {
                    $unitId = $unitId->id;
                    $incomingPackage = new IncomingPackage();
                    $incomingPackage->receive_date = $date;
                    $incomingPackage->description = $description;

                    $img = str_replace('data:image/jpeg;base64,', '', $base64Image);
                    $img = str_replace(' ', '+', $img);
                    $imgData = base64_decode($img);
                    $strCode = $officer_id . $unit_no . $date . 'package';
                    $imgFileName = 'img-package-' . $unit_no . md5($strCode) . '.png';
                    $imgFileDirectory = '../public/packages/photos/' . $imgFileName;
                    file_put_contents($imgFileDirectory, $imgData);

                    $incomingPackage->photo_url = $imgFileName;

                    $strCode = $officer_id . $unit_no . $date . 'package';
                    $verificationCode = hash('sha256', $strCode);
                    $qrFileName = 'package-qr-' . $unit_no . md5($strCode) . '.png';
                    $qrFileDirectory = '../public/packages/qr-code/' . $qrFileName;
                    QrCode::size(500)->margin(2)->color(0, 33, 71)->format('png')->generate($verificationCode, $qrFileDirectory);

                    $incomingPackage->verification_code = $verificationCode;
                    $incomingPackage->qr_url = $qrFileName;
                    $incomingPackage->unit_id = $unitId;
                    $incomingPackage->receiving_security_officer_id = $officer_id;
                    $incomingPackage->save();

                    $arrResponse = ['status' => 'success'];
                } else {
                    $unitAtOtherTower = Unit::select('id')->where('unit_no', $unit_no)->first();
                    if ($unitAtOtherTower != null) {
                        $arrResponse = ['status' => 'othertower'];
                    } else {
                        $arrResponse = ['status' => 'notfound'];
                    }
                }
            } else {
                $arrResponse = ['status' => 'securityprob', 'securitystatus' => $statusSecurity];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }

        return $arrResponse;
    }
    public function secPackageCollection(Request $request)
    {
        $verification_code = $request->get('code');
        $officer_id = $request->get('officer');
        $tower_id = $request->get('tower');

        $token = $request->get('token');
        $tokenValidation = Helper::validateToken($token);

        if ($tokenValidation == true) {
            $statusSecurity = Helper::checkSecurityShift($officer_id, $tower_id);
            if ($statusSecurity == 'exist') {
                $package = IncomingPackage::where('verification_code', $verification_code)->first();
                if ($package != null) {
                    $package->pickup_date = date('Y-m-d H:i:s');
                    $package->pickup_security_officer_id = $officer_id;
                    $arrResponse = ["status" => "success", "unit_no"=>$package->unit->unit_no];
                } else {
                    $arrResponse = ["status" => "notfound"];
                }
            }
            else{
                $arrResponse = ['status' => 'securityprob', 'securitystatus' => $statusSecurity];
            }
        } else {
            $arrResponse = ["status" => "notauthenticated"];
        }
        return $arrResponse;
    }
}
