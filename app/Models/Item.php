<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [

        'item_name',
        'unit_name',
        'pieces_perUnit',
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
        return $this->hasMany(Supplier::class, 'item_id', 'id');
    }

    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class, 'item_id', 'id');
    }
}
