<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'ファッション',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'ベビー・キッズ',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'ゲーム・おもちゃ・グッズ',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'ホビー・楽器・アート',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'チケット',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => '本・雑誌・漫画',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'スマホ・タブレット・パソコン',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'テレビ・オーディオ・カメラ',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => '生活家電・空調',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'スポーツ',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'アウトドア・釣り・旅行用品',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'ＣＤ・ＤＶＤ・ブルーレイ',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'コスメ・美容',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'ダイエット・健康',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => '食品・飲料・酒',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'キッチン・日用品・その他',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => '家具・インテリア',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'ペット用品',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'ＤＩＹ・家具',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'フラワー・ガーデニング',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => 'ハンドメイド・手芸',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'name' => '車・バイク・自転車',
        ];
        DB::table('categories')->insert($param);
    }
}
