<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualWithdrawal extends Model
{
    protected $fillable = [
        'date','code','item','unit','quantity','voucher','reason','receiver',
        'request_department','notes','created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:4',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

        // علاقة مع الوارد (IncomingItem)
        public function incomingItem()
        {
            return $this->belongsTo(IncomingItem::class, 'code', 'code');
        }
}
