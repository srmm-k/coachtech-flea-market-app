<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'category_id',
        'condition',
        'product_name',
        'brand_name',
        'description',
        'price',
        'user_id',
        'is_sold'
    ];

    protected $casts = [
        'category' => 'array',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getConditionLabelAttribute()
    {
        $conditions =[
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ];

        return $conditions[$this->condition] ?? '未設定';
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes', 'listing_id', 'user_id')->withTimestamps();
    }
}
