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
        'quantity', 
        'category_id',
        'classification_id',
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
    /**
     * Relationship with the Classification model
     */
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }
    /**
     * Relationship with the Debtor model
     */
    public function debtor()
    {
        return $this->hasMany(Debtor::class);
    }

}
