<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingItem extends Model
{
    protected $fillable = [
        'department_id', 'date', 'code', 'item', 'unit', 'quantity', 'supplier'
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:4',
        'min_quantity' => 'decimal:4'
    ];

    // العلاقة مع جدول السحوبات اليدوية
    public function withdrawals()
    {
        return $this->hasMany(ManualWithdrawal::class, 'code', 'code'); 
    }

    // العلاقة مع جدول الأقسام
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
