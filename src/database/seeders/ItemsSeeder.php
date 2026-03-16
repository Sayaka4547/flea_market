<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsSeeder extends Seeder
{
    public function run()
    {
        $items = [
        ['name' => '腕時計', 'bland' => 'Rolax', 'price' => 15000, 'condition' => '良好', 'description' => 'スタイリッシュなデザインのメンズ腕時計','image' => 'items/img10.jpg'],
        ['name' => 'HDD', 'bland' => '西芝', 'price' => 5000, 'condition' => '目立った傷や汚れなし', 'description' => '高速で信頼性の高いハードディスク','image' => 'items/img09.jpg'],
        ['name' => '玉ねぎ3束', 'bland' => 'なし', 'price' => 300, 'condition' => 'やや傷や汚れあり', 'description' => '新鮮な玉ねぎ3束のセット','image' => 'items/img08.jpg'],
        ['name' => '革靴', 'bland' => '', 'price' => 4000, 'condition' => '状態が悪い', 'description' => 'クラシックなデザインの革靴','image' => 'items/img07.jpg'],
        ['name' => 'ノートPC', 'bland' => '', 'price' => 45000, 'condition' => '良好', 'description' => '高性能なノートパソコン','image' => 'items/img06.jpg'],
        ['name' => 'マイク', 'bland' => 'なし', 'price' => 8000, 'condition' => '目立った傷や汚れなし', 'description' => '高音質のレコーディング用マイク','image' => 'items/img05.jpg'],
        ['name' => 'ショルダーバッグ', 'bland' => '', 'price' => 3500, 'condition' => 'やや傷や汚れあり', 'description' => 'おしゃれなショルダーバッグ','image' => 'items/img04.jpg'],
        ['name' => 'タンブラー', 'bland' => 'なし', 'price' => 500, 'condition' => '状態が悪い', 'description' => '使いやすいタンブラー','image' => 'items/img03.jpg'],
        ['name' => 'コーヒーミル', 'bland' => 'Starbacks', 'price' => 4000, 'condition' => '良好', 'description' => '手動のコーヒーミル','image' => 'items/img02.jpg'],
        ['name' => 'メイクセット', 'bland' => '', 'price' => 2500, 'condition' => '目立った傷や汚れなし', 'description' => '便利なメイクアップセット','image' => 'items/img01.jpg'],
    ];

    foreach ($items as $item) {
        DB::table('items')->insert([
            'user_id'       => 1,
            'name'          => $item['name'],
            'bland'         => $item['bland'],
            'price'         => $item['price'],
            'condition'     => $item['condition'],
            'description'   => $item['description'],
            'image'         => $item['image'],
            'status'        => 'on_sale',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
    }
}
