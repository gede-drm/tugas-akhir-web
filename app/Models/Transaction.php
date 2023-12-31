<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function tenant(){
        return $this->belongsTo(Tenant::class);
    }
    public function servicePermission(){
        return $this->hasOne(Permission::class, 'service_transaction_id');
    }
    public function products(){
        return $this->belongsToMany(Product::class, 'product_transaction_detail', 'transaction_id', 'product_id')->withPivot('quantity', 'price', 'rating', 'review');
    }
    public function services(){
        return $this->belongsToMany(Service::class, 'service_transaction_detail', 'transaction_id', 'service_id')->withPivot('quantity', 'price', 'rating', 'review');
    }
    public function statuses(){
        return $this->hasMany(TransactionStatus::class, 'transaction_id', 'id');
    }
}
