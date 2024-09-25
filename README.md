# COACHTECHフリマ

## 概要
COACHTECHが提供する独自のフリマアプリケーションです。会員登録なしでも商品の詳細閲覧は可能ですが、
会員登録していただくと購入、出品、評価コメント投稿、お気に入り追加等の機能をご利用可能になります。
![coachtech free-market ホーム画面 jpg](https://github.com/user-attachments/assets/83604f0d-e62c-491a-a9b3-d049f4d458a5)

## 作成した目的
クライアントよりCOACHTECHブランドのアイテムを独自のフリマアプリで出品したいとの要望があったため、
要望に添った機能を持つアプリを作成しました。

## アプリケーションURL
### ローカル環境
http://localhost

## 機能一覧
・会員登録機能
・ログイン、ログアウト機能
・商品一覧表示機能
・商品詳細取得機能
・商品お気に入り一覧取得機能
・商品お気に入り追加、削除機能
・ユーザー情報取得機能
・ユーザー購入商品一覧取得機能
・ユーザー出品商品一覧取得機能
・プロフィール作成、変更機能
・商品評価コメント追加、削除機能
・出品機能
・配送先変更機能
・商品購入、決済機能
・支払い方法の選択・変更機能
・管理者ログイン、ログアウト機能
・管理者メール送信機能
・画像アップロード機能

## 使用技術
・Laravel 8.83.27
・nginx 1.21.1
・PHP 8.1.29 
・html
・css
・mysql 15.1 

## テーブル設計

## ER図

## 環境構築
## 1 Gitファイルをクローンする
git clone git@github.com:9136424556/free-market

## 2 Dokerコンテナを作成する
docker-compose up -d --build

## 3 Laravelパッケージをインストールする
docker-compose exec php bash
でPHPコンテナにログインし
composer install

## 4 .envファイルを作成する
PHPコンテナにログインした状態で
cp .env.example .env
作成した.envファイルの該当欄を下記のように変更
DB_HOST=mysql
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_FROM_ADDRESS=s.h.0307gt4@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

.envファイルの最後に追加
STRIPE_KEY=stripeで取得した公開キー
STRIPE_SECRET=stripeで取得したシークレットキー

## 5 テーブルの作成
docker-compose exec php bash
でPHPコンテナにログインし(ログインしたままであれば上記コマンドは実行しなくて良いです。)
php artisan migrate

## 6 ダミーデータ作成
PHPコンテナにログインした状態で
php artisan db:seed

## 7 アプリケーション起動キーの作成
PHPコンテナにログインした状態で
php artisan key:generate

## 8 シンボリックリンクの作成
PHPコンテナにログインした状態で
php artisan storage:link

## その他
管理者アカウント　
メールアドレス　coachtech@coachtech.com
パスワード　coachtech
