<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function permission(){
        return $this->belongsTo(Permission::class, 'permission_id');
    }
    public function permits(){
        return $this->hasMany(Permission::class, 'permit_id', 'id');
    }
}
