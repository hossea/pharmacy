<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
    ];

    /**
     * Relationship with the Medicine model
     */
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

}
