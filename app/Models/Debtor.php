<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debtor extends Model
{
    // Allow mass assignment for the following attributes
    protected $fillable = [
        'name',
        'phone',
        'amount_owed',
        'sale_id',
    ];
    /**
     * Define the relationship with the Medicine model
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
