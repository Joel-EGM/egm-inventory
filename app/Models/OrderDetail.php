<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [

        'supplier_id',
        'item_id',
        'order_id',
        'unit_id',
        'quantity',
        'price',
        'total_amount',
        'order_status',
        'is_received',
        'order_type',
    ];

    protected $casts = [
        'order_date' => 'date:Y-m-d'
    ];

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function prices()
    {
        return $this->belongsTo(ItemPrice::class, 'unit_id', 'id');
    }

    public function branches()
    {
        return $this->hasManyThrough(Branch::class, Order::class, 'branch_id', 'id');
    }

    public function stocks()
    {
        return $this->belongsTo(Stock::class, 'item_id', 'item_id');

    }
}
