# flea_market

## 環境構築

### Dockerビルド

```bash
git clone
docker-compose up -d --build
```

> ※ DockerfileにてPHP GD拡張を有効化しています。画像アップロード機能の動作に必要です。

### Laravel環境構築

```bash
docker-compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```

`.env`の以下の環境変数を適宜設定してください
| 変数名 | 説明 |
|---|---|
| `DB_DATABASE` | データベース名 |
| `DB_USERNAME` | DBユーザー名 |
| `DB_PASSWORD` | DBパスワード |
| `STRIPE_KEY` | Stripe公開キー |
| `STRIPE_SECRET` | Stripeシークレットキー |

## 開発環境

- トップ画面：http://localhost/
- ユーザー登録：http://localhost/register
- ログイン画面：http://localhost/login
- phpMyAdmin：http://localhost:8080/

## 使用技術

- PHP 8.1.34
- Laravel Framework 8.83.29
- HTML / CSS
- JavaScript (fetch API)
- mysql:8.0.26
- nginx:1.21.1
- Docker / Docker Compose
- Laravel Fortify 1.19.1（認証機能）
- Stripe（決済機能）API キーを各自で用意してください

## ER図
<img width="1621" height="851" alt="flea-market drawio (3)" src="https://github.com/user-attachments/assets/0e452e1c-b08b-45ee-b231-7cfd43450ea2" />
