<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InboundRow extends Model
{
    protected $fillable = [
        'inbound_batch_id','department','doc_date','code','item_name',
        'unit','quantity','supplier','errors'
    ];

    protected $casts = [
        'doc_date' => 'date',
        'errors' => 'array',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(InboundBatch::class, 'inbound_batch_id');
    }
}
