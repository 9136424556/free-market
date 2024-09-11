<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'condition' => '未使用',
        ];
        DB::table('conditions')->insert($param);

        $param = [
            'condition' => '未使用に近い',
        ];
        DB::table('conditions')->insert($param);

        $param = [
            'condition' => '目立った傷や汚れなし',
        ];
        DB::table('conditions')->insert($param);

        $param = [
            'condition' => 'やや傷や汚れあり',
        ];
        DB::table('conditions')->insert($param);

        $param = [
            'condition' => '傷や汚れあり',
        ];
        DB::table('conditions')->insert($param);

        $param = [
            'condition' => '全体的に状態が悪い',
        ];
        DB::table('conditions')->insert($param);
    }
}
