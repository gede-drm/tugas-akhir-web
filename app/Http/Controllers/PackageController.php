<?php

namespace App\Http\Controllers;

use App\Models\IncomingPackage;
use App\Models\Unit;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PackageController extends Controller
{
    // API
    public function secPackagePendingList(Request $request)
    {
        $tower = $request->get('tower');
        $packages = IncomingPackage::select('id', 'receive_date', 'photo_url')->where('tower_id', $tower)->whereNull('pickup_date')->get();
        $arrResponse = [];
        if (count($packages) > 0) {
            foreach ($packages as $package) {
                $package['unit_no'] = $package->unit->unit_no;
            }
            $arrResponse = ['status'=>'success', 'data'=>$packages];
        }
        else{
            $arrResponse = ['status'=>'empty'];
        }
        return $arrResponse;
    }
    public function secPackageDetail(Request $request)
    {
        $idPackage = $request->get('package_id');
    }
    public function secPackageEntry(Request $request)
    {
        // Belum Selesai
        $date = date('Y-m-d H:i:s');
        $description = $request->get('description');
        $officer_id = $request->get('officer');
        $unit_id = $request->get('unit');

        $incomingPackage = new IncomingPackage();
        $incomingPackage->receive_date = $date;
        $incomingPackage->description = $description;
        $incomingPackage->photo_url = "";

        $unitNo = Unit::select('unit_no')->where('id', $unit_id)->first()->unit_no;
        $strCode = $officer_id . $unitNo . $date . 'package';
        $verificationCode = hash('sha256', $strCode);
        $qrFileName = 'package-qr-' . $unitNo . md5($strCode) . '.png';
        $qrFileDirectory = '../public/packages-qr/' . $qrFileName;
        QrCode::size(500)->margin(2)->color(0, 33, 71)->format('png')->generate($verificationCode, $qrFileDirectory);

        $incomingPackage->verification_code = $verificationCode;
        $incomingPackage->qr_url = $qrFileName;
        $incomingPackage->unit_id = 1;
        $incomingPackage->receiving_security_officer_id = 1;
    }
    public function secPackageCollection(Request $request)
    {
    }
}
