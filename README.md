環境構築

Dockerビルド
・ git clone git@gtihub.com:coachtech-material/laravel-docker-template.git
・ mv laravel-docker-template coachtech-flea-market-app
・ docker-compose up -d --build

laravel環境構築
・ docker-compose exec php bash
・ composer install
・ composer -v
・ cp .env.example .env,環境変数を適宜変更
・ php artisan make:command MakeBladeCommand,blade.phpのテンプレート作成
