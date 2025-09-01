<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    protected $fillable = [
        'date','code','item','unit','requested_qty','supplier',
        'request_department','requester_name','status','reason','notes','created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'requested_qty' => 'decimal:4',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
