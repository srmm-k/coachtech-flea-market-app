<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => '1',
            'buyer_id' => 'null',
            'image_path' => asset('images/Living+Room+Laptop.jpg'),
            'category' => '家電',
            'condition' => '良好',
            'product_name' => 'ノートPC',
            'brand_name' => 'apple',
            'description' => '高性能なノートパソコン',
            'price' => '45000',
            'is_sold' => '0',
        ];
        DB::table('listings')->insert($param);
    }
}