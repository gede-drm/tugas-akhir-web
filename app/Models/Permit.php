<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function permission(){
        return $this->belongsTo(Permission::class, 'permission_id');
    }
    public function worker(){
        return $this->belongsTo(Worker::class, 'worker_id');
    }
    public function security(){
        return $this->belongsTo(SecurityOfficer::class, 'security_officer_id');
    }
}
