<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public static function like($user_id, $item_id)
    {
        $param = [
            "user_id" => $user_id,
            "item_id" => $item_id,
        ];
        $like = Like::create($param);

        return $like;
    }
}
