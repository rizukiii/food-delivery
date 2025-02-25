<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_amount',
        'order_payment',
        'payment_status',
        'order_status',
        'total_tax_amount',
        'order_note',
        'delivery_charge',
        'schedule_at',
        'otp',
        'refund_requested',
        'refunded',
        'scheduled',
        'details_count',
        'delivery_address_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(Address::class, 'delivery_address_id');
    }
}
