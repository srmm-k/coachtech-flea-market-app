<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'listing_id',
        'price',
        'payment_method',
        'stripe_payment_intent_id',
        'status',
        'shipping_postcode',
        'shipping_address',
        'shipping_building',
    ];

    public function user()
    {
        return $this->belongstTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
