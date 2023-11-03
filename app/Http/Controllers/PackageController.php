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
        $packages = IncomingPackage::select('id', 'receive_date', 'photo_url', 'unit_id')->whereRelation('unit', 'tower_id', $tower)->whereNull('pickup_date')->get();
        $arrResponse = [];
        if (count($packages) > 0) {
            foreach ($packages as $package) {
                $package['unit_no'] = $package->unit->unit_no;
            }
            $arrResponse = ['status' => 'success', 'data' => $packages];
        } else {
            $arrResponse = ['status' => 'empty'];
        }
        return $arrResponse;
    }
    public function secPackageDetail(Request $request)
    {
        $idPackage = $request->get('package_id');
    }
    public function secPackageEntry(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $description = $request->get('description');
        $officer_id = $request->get('officer');
        $unit_id = $request->get('unit');
        $base64Image = $request->get('image');

        $incomingPackage = new IncomingPackage();
        $incomingPackage->receive_date = $date;
        $incomingPackage->description = $description;

        $unitNo = Unit::select('unit_no')->where('id', $unit_id)->first()->unit_no;

        $img = str_replace('data:image/jpeg;base64,', '', $base64Image);
        $img = str_replace(' ', '+', $img);
        $imgData = base64_decode($img);
        $strCode = $officer_id . $unitNo . $date . 'package';
        $imgFileName = 'img-qr-' . $unitNo . md5($strCode) . '.png';
        $imgFileDirectory = '../public/packages/photos/' . $imgFileName;
        file_put_contents($imgFileDirectory, $imgData);

        $incomingPackage->photo_url = $imgFileName;

        $strCode = $officer_id . $unitNo . $date . 'package';
        $verificationCode = hash('sha256', $strCode);
        $qrFileName = 'package-qr-' . $unitNo . md5($strCode) . '.png';
        $qrFileDirectory = '../public/packages/qr-code/' . $qrFileName;
        QrCode::size(500)->margin(2)->color(0, 33, 71)->format('png')->generate($verificationCode, $qrFileDirectory);

        $incomingPackage->verification_code = $verificationCode;
        $incomingPackage->qr_url = $qrFileName;
        $incomingPackage->unit_id = 1;
        $incomingPackage->receiving_security_officer_id = 1;
        $incomingPackage->save();

        $arrResponse = ['status' => 'success'];

        return $arrResponse;
    }
    public function secPackageCollection(Request $request)
    {
    }
}
