COACHTECHフリマアプリ

このアプリケーションは、ユーザー登録、商品の出品・購入、コメント・いいね、購入機能を持つフリマサイトです。

環境構築

Dockerビルド  

【コマンドライン上】

・ git clone git@gtihub.com:coachtech-material/laravel-docker-template.git  
・ mv laravel-docker-template coachtech-flea-market-app  
・ cd coachtech-flea-market-app  
・ docker-compose up -d --build (docker-compose.yml,nginx,php(dockerfile),mysqlを適宜変更)  

laravel環境構築  

・ docker-compose exec php bash  
・ composer install  
・ composer -v  
・ cp .env.example .env,環境変数を適宜変更  
・ php artisan make:command MakeBladeCommand,blade.phpのテンプレート作成  
・ php artisan make:blade login ...各必要なviewの作成  
・ php artisan make:controller ProfileController ...各必要なcontrollerを作成（アッパーキャメル）  
・ php artisan make:model Profile ...各必要なmodelを作成（アッパーキャメル）  
・ php artisan make:migration create_profiles_table ...各必要なtableを作成（スネークケース）  
・ php artisan make:request LoginRequest ...各必要なRequestを作成（アッパーキャメル）  
・ マイグレーションファイル編集後、php artisan migrate　実行  
・ php artisan make:seeder ListingsTablesSeeder ...各必要なSeederを作成（アッパーキャメル）  
・ ダミーデータの作成  
・ php artisan db:seed 実行  

開発環境

・トップページ http://localhost:1580/  
・ログインページ http://localhost:1580/login  
・会員登録ページ http://localhost:1580/register  
・メール認証誘導ページ http://localhost:1580/verify-email-info  
・プロフィール設定ページ http://localhost:1580/mypage/profile  
・商品出品ページ http://localhost:1580/sell  
・プロフィールページ http://localhost:1580/mypage  
・商品詳細ページ http://localhost:1580/item/7 （商品によって、item/nが変更される）  
・商品購入ページ　http://localhost:1580/purchase/6?　（商品によって、n/?が変更される）  
・送付先変更ページ　http://localhost:1580/purchase/address/1　（商品IDによって末尾の数字が変更される）  

使用技術（実行環境）

・ php 7.4.33  
・ laravel 8.83.8  
・ MySql 8.0.26  
・ nginx 1.21.1  

ER図
<img width="1227" height="701" alt="スクリーンショット 2025-07-28 1 03 02" src="https://github.com/user-attachments/assets/ef62163c-0df6-4427-bca1-79adcb6a03dc" />
