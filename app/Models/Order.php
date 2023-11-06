<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'branch_id',
        'order_date',
        'order_status',
        'created_by',
        'recieved_by',
        'or_number',
        'or_date',
        'order_completed'
    ];


    public function getIsEditedAttribute()
    {
        $ymd = strtotime($this->attributes['updated_at']);
        $format = date('Y-m-d', $ymd);
        return ($format === Carbon::now()->format('Y-m-d')) ? true : false;
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}
