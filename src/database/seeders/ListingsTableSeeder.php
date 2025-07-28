<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ListingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');
        $specificListings = [
            [
                'product_name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'images/Armani+Mens+Clock.jpg',
                'condition' => '良好',
                'category' => ['メンズ', 'アクセサリー', 'ファッション'],
                'is_sold' => false,
            ],
            [
                'product_name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'images/oOKDeLRtcQMM2YW1ugBkcUxeNlCM4wt8UcFQ9Tut.jpg',
                'condition' => '目立った傷や汚れなし',
                'category' => ['家電'],
                'is_sold' => false,
            ],
            [
                'product_name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'images/sjXsMS8VL1ED7U1IXBKSWuTCXd4Fz5cCWzWrutFW.jpg',
                'condition' => 'やや傷や汚れあり',
                'category' => ['キッチン'],
                'is_sold' => true,
            ],
            [
                'product_name' => '革靴',
                'price' =>4000 ,
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'images/vLN1HiMTtdPWxFqddDuEE46l7ajPxR6adh2o1Pgs.jpg',
                'condition' => '状態が悪い',
                'category' => ['メンズ'],
                'is_sold' => false,
            ],
            [
                'product_name' => 'ノートPC',
                'price' =>45000 ,
                'description' => '高性能なノートパソコン',
                'image_path' => 'images/Living+Room+Laptop.jpg',
                'condition' => '良好',
                'category' => ['家電'],
                'is_sold' => false,
            ],
            [
                'product_name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'images/Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
                'category' => ['家電'],
                'is_sold' => false,
            ],
            [
                'product_name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'images/Purse+fashion+pocket.jpg',
                'condition' => 'やや傷や汚れあり',
                'category' => ['レディース'],
                'is_sold' => false,
            ],
            [
                'product_name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'image_path' => 'images/Tumbler+souvenir.jpg',
                'condition' => '状態が悪い',
                'category' => ['キッチン'],
                'is_sold' => false,
            ],
            [
                'product_name' => 'コーヒーミル',
                'price' =>4000 ,
                'description' => '手動のコーヒーミル',
                'image_path' => 'images/Waitress+with+Coffee+Grinder.jpg',
                'condition' => '良好',
                'category' => ['キッチン'],
                'is_sold' => false,
            ],
            [
                'product_name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'image_path' => 'images/外出メイクアップセット.jpg',
                'condition' => '目立った傷や汚れなし',
                'category' => ['レディース', 'コスメ'],
                'is_sold' => false,
            ],
        ];

        foreach ($specificListings as $listingData) {
            $seller = User::inRandomOrder()->first();
            if (!$seller) {
                $seller = User::factory()->create();
            }

            $listing = Listing::create([
                'user_id' => $seller->id,
                'product_name' => $listingData['product_name'],
                'price' => $listingData['price'],
                'description' => $listingData['description'],
                'image_path' => $listingData['image_path'],
                'condition' => $listingData['condition'],
                'category' => json_encode($listingData['category']),
                'brand_name' => $listingData['brand_name'] ?? $faker->optional(0.5)->company(),
                'is_sold' => $listingData['is_sold'],
                'buyer_id' => $listingData['is_sold'] ? (User::inRandomOrder()->where('id', '!=', $seller->id)->first() ?? User::factory()->create())->id : null,
            ]);

            if ($listingData['is_sold']) {
                $buyer = User::inRandomOrder()->where('id', '!=', $seller->id)->first();
                if (!$buyer) {
                    $buyer = User::factory()->create();
                }

                $listing->update([
                    'buyer_id' => $buyer->id,
                ]);

                $buyerProfile = $buyer->profile()->first();
                if (!$buyerProfile) {
                    $buyerProfile = \App\Models\Profile::factory()->create(['user_id' => $buyer->id]);
                }

                Purchase::create([
                    'user_id' => $buyer->id,
                    'listing_id' => $listing->id,
                    'price' => $listing->price,
                    'payment_method' => $faker->randomElement(['card', 'convenience']),
                    'stripe_payment_intent_id' => 'pi_' . Str::random(20),
                    'status' => 'succeeded',
                    'shipping_postcode' => $buyerProfile->postcode,
                    'shipping_address' => $buyerProfile->address,
                    'shipping_building' => $buyerProfile->building_name,
                ]);
            }
        }
    }
}