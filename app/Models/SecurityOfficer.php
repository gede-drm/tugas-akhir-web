<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityOfficer extends Model
{
    use HasFactory;
    public $table = 'security_officers';
    public $timestamps = false;
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function checkins(){
        return $this->hasMany(SecurityOfficerCheckin::class, 'security_officer_id', 'id');
    }
    public function receivePackages(){
        return $this->hasMany(IncomingPackage::class, 'receiving_security_officer_id', 'id');
    }
    public function pickupPackages(){
        return $this->hasMany(IncomingPackage::class, 'pickup_security_officer_id', 'id');
    }
}
