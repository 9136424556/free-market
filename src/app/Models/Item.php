<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
       'name',
       'price',
       'description',
       'img_url',
       'user_id',
       'condition_id',
    ];
    public function condition()
    {
       return $this->belongsTo(Condition::class);
    }

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function sold()
    {
        return $this->belongsToMany(User::class, 'sold_items', 'item_id', 'user_id')
         ->withTimestamps();
    }

   
}
