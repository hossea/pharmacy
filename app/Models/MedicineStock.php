<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineStock extends Model
{
    protected $fillable = ['medicine_id', 'quantity'];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
