<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sold_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
