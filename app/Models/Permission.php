<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    public function serviceTransaction(){
        return $this->belongsTo(Transaction::class);
    }
    public function managementApproval(){
        return $this->belongsTo(User::class, 'management_id');
    }
    public function workers(){
        return $this->hasMany(Worker::class, 'permission_id', 'id');
    }
    public function permits(){
        return $this->hasMany(Permit::class, 'permission_id', 'id');
    }
}
