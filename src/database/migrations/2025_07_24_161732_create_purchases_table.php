<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 購入者
            $table->foreignId('listing_id')->constrained()->onDelete('cascade'); // 購入した商品
            $table->integer('price'); //価格はstripeとの連携で重要なので、他のカラムより上
            $table->string('payment_method'); // card / convenience 支払い方法
            $table->string('stripe_payment_intent_id')->nullable()->unique();
            $table->string('status')->default('pending'); // 支払いステータス (pending, completed, failedなど)
            //配送先データ
            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_building')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
