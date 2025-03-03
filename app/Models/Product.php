<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'location',
        'type_id'
    ];

    protected $casts = [
        'image' => 'array'
    ];

    public function reduceStock(int $quantity){
        if($this->stock < $quantity){
            throw new \Exception('Stock tidak cukup!');
        }

        $this->stock -= $quantity;
        $this->save();
    }

    public function increaseStock(int $quantity){
        if($quantity > 0){
            $this->increment('quantity',$quantity);
        }
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }
}
