<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'branch_id',
        'order_id',
        'item_id',
        'category_id',
        'quantity',
        'price',
        'qty_out'
    ];

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(Stock::class, 'item_id', 'item_id');
    }
}
