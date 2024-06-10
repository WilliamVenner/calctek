<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalcHistoryEntry extends Model
{
    use HasFactory;

    public $incrementing = true;

    // We only need created_at
    // A calculation history entry is immutable
    public $timestamps = ['created_at'];
    const UPDATED_AT = null;

    /**
     * Get the user that created this entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
