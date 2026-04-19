# flea_market

##環境構築

###Dockerビルド

- 'git clone'
- 'docker-compose up -d --build'

###Laravel環境構築

- 'docker-compose exec php bash'
- 'composer install'
- 'cp .env.example .env'（.envの環境変数を適宜変更）
- 'php artisan key:generate'
- 'php artisan migrate'
- 'php artisan db:seed'

##開発環境

- お問合せ画面：http://localhost/
- ユーザー登録：http://localhost/register
- 管理者画面：http://localhost/admin
- phpMyAdmin：http://localhost:8080/

##使用技術

- PHP 8.1.34
- Laravel Framework 8.83.29
- HTML / CSS
- JavaScript (fetch API)
- mysql:8.0.26
- nginx:1.21.1
- Docker / Docker Compose
- Laravel Fortify 1.19.1（認証機能）

##ER図
