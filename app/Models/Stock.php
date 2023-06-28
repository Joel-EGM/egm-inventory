<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'price',
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
