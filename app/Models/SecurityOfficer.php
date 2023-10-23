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
}
