<?php

// app/Models/InventoryTransaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = ['inventory_item_id', 'type', 'quantity', 'note', 'user_id'];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

