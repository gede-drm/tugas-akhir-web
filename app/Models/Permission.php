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
    public function workers(){
        return $this->hasMany(Worker::class, 'permit_id', 'id');
    }
    public function permits(){
        return $this->hasMany(Permission::class, 'permit_id', 'id');
    }
}
