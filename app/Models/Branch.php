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
        'branch_contactNo',
        'status'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'branch_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'branch_id', 'id');
    }
}
