<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingPackage extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function receivingSecurity(){
        return $this->belongsTo(SecurityOfficer::class, 'receiving_security_officer_id');
    }
    public function pickupSecurity(){
        return $this->belongsTo(SecurityOfficer::class, 'pickup_security_officer_id');
    }
}
