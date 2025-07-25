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
    ];
}
