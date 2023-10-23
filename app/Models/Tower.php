<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tower extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function units(){
        return $this->hasMany(Unit::class, 'tower_id', 'id');
    }
    public function checkins(){
        return $this->hasMany(SecurityOfficerCheckin::class, 'tower_id', 'id');
    }
}
