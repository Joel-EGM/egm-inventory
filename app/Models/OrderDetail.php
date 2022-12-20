<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'supplier_id',
        'item_id',
        'order_id',
        'unit_name',
        'quantity',
        'price',
        'total_amount',
        'order_status',
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
}
