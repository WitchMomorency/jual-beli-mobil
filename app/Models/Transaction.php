<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaction extends Model
{
    // Kolom yang boleh diisi secara massal
    protected $fillable = ['customer_id', 'product_id', 'quantity', 'transaction_date']; // Ubah disini

    protected $casts = [
        'transaction_date' => 'date:Y-m-d',
    ];

    // Relasi ke tabel customers
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke tabel products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected function totalPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->quantity * $this->product->price,
        );
    }
}