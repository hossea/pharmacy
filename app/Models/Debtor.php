<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debtor extends Model
{
    use HasFactory;
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
    public function medicine()
    {
        return $this->sale->medicine();
    }


}
