<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'name',
        'manager',       // ✅ أضفناه
        'description'    // ✅ أضفناه
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
