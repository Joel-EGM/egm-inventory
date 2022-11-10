<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [

        'branch_name',
        'branch_address',
        'branch_contactNo'
    ];

    public function order()
    {
        return $this->hasMany(Order::class, 'branch_id', 'id');
    }
}
