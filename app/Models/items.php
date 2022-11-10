<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class items extends Model
{
    protected $table = 'items';

    protected $fillable = [

        'item_name',
        'unit_name',
        'pieces_perUnit',
    ];

    public function stock()
    {
        return $this->hasMany(Stock::class, 'item_id', 'id');
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'item_id', 'id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'item_id', 'id');
    }
}
