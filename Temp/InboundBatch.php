<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InboundBatch extends Model
{
    protected $fillable = [
        'original_filename','file_path','status',
        'total_rows','processed_rows','failed_rows',
        'error_file_path','started_at','finished_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function rows(): HasMany
    {
        return $this->hasMany(InboundRow::class);
    }
}
