<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderdetails extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'item_id',
        'order_id',
        'quantity',
        'price',
        'total_amount',
        'order_status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
