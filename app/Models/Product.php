<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function tenant(){
        return $this->belongsTo(Tenant::class);
    }
    public function transactions(){
        return $this->belongsToMany(Transaction::class, 'product_transaction_detail', 'product_id', 'transaction_id')->withPivot('quantity', 'price', 'rating', 'review');
    }
}
