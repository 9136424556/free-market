<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User; 
use App\Models\Condition; 
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word, // ダミーの名前を生成
            'price' => $this->faker->numberBetween(100, 10000), // 100円から10000円の間のダミー価格を生成
            'description' => $this->faker->sentence, // ダミーの説明文を生成
            'img_url' => $this->faker->imageUrl(640, 480), // ダミー画像のURLを生成
            'user_id' => User::factory(), // ユーザーのファクトリを使って関連するユーザーIDを生成
            'condition_id' => Condition::factory(), // Conditionのファクトリを使って関連するcondition_idを生成
        ];
    }
}
