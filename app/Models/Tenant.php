<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->hasMany(Product::class, 'tenant_id', 'id');
    }
    public function services(){
        return $this->hasMany(Service::class, 'tenant_id', 'id');
    }
}
