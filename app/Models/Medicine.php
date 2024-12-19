<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $casts = [
        'expiry_date' => 'date',
    ];

    // Specify which attributes are mass-assignable
    protected $fillable = [
        'medicine_id',
        'name',
        'company',
        'price',
        'quantity', // Fixed consistency (use lowercase)
        'category_id',
        'expiry_date',
    ];

    /**
     * Relationship with the Sale model
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Relationship with the Sale model
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
