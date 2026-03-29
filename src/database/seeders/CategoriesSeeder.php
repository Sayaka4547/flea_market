<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // 外部キー制約を無効化
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // 外部キー制約を再度有効化

        DB::table('categories') ->insert([
        ['name' => 'ファッション'],
        ['name' => '家電'],
        ['name' => 'インテリア'],
        ['name' => 'レディース'],
        ['name' => 'メンズ'],
        ['name' => 'コスメ'],
        ['name' => '本'],
        ['name' => 'ゲーム'],
        ['name' => 'スポーツ'],
        ['name' => 'キッチン'],
        ['name' => 'ハンドメイド'],
        ['name' => 'アクセサリー'],
        ['name' => 'おもちゃ'],
        ['name' => 'ベビー・キッズ'],
        ]);
    }
}
