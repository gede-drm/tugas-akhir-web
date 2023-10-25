<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityOfficerCheckin extends Model
{
    use HasFactory;
    public $table = 'security_officer_checkins';
    public $timestamps = false;
    public function security(){
        return $this->belongsTo(SecurityOfficer::class, 'security_officer_id');
    }
    public function tower(){
        return $this->belongsTo(Tower::class, 'tower_id');
    }
    public function managementIn(){
        return $this->belongsTo(User::class, 'management_checkin_id');
    }
    public function managementOut(){
        return $this->belongsTo(User::class, 'management_checkout_id');
    }
}
