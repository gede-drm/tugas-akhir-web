<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WMALog extends Model
{
    use HasFactory;
    public $table = "wma_logs";
    public $timestamps = false;
    
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
}
