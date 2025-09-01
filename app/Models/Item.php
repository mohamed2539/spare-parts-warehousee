<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'quantity', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }
}

