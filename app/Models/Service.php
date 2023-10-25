<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function tenant(){
        return $this->belongsTo(Tenant::class);
    }
    public function transactions(){
        return $this->belongsToMany(Transaction::class, 'service_transaction_detail', 'service_id', 'transaction_id')->withPivot('quantity', 'price', 'rating', 'review');
    }
}
