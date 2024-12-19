<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    // Allow mass assignment for the following attributes
    protected $fillable = [
        'medicine_id',
        'quantity_sold',
        'price_per_unit',
        'total_price',
        'payment_method',
        'discount',
        'sold_by',
    ];

    /**
     * Define the relationship with the Medicine model
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
    public function debtor()
    {
        return $this->hasOne(Debtor::class);
    }
}
