<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'supplier_id',
        'item_name',
        'unit_name',
        'pieces_perUnit',
        'fixed_unit',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'item_id', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'item_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'item_id', 'id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class, 'item_id', 'id');
    }
}
