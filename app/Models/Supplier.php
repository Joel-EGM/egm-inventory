<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [

        'suppliers_name',
        'suppliers_email',
        'suppliers_contact',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'item_id', 'id');
    }

    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class, 'price_id', 'id');
    }
}
