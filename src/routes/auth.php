<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    //会員登録ページ
    Route::get('/register', [RegisteredUserController::class, 'create'])
                ->name('register');
    //会員登録処理
    Route::post('register', [RegisteredUserController::class, 'store']);

    //ログインページ
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->name('login'); 
    //ログイン処理
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);


    //ログアウト処理（ボタンを押して実行）
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
   //お気に入り追加、削除
    Route::post('/like/{item_id}', [LikeController::class, 'create'])->name('like');
    Route::post('/unlike/{item_id}', [LikeController::class, 'delete'])->name('unlike');
   //マイページ画面
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
   //プロフィール設定・編集
    Route::get('/mypage/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/mypage/profile/{id}/edit',[UserController::class, 'edit'])->name('edit');
     //プロフィール作成
    Route::post('/mypage/profile/store', [UserController::class, 'storeprofile'])->name('storeprofile');
     //プロフィール変更
    Route::put('/mypage/profile/{id}', [UserController::class, 'changeprofile'])->name('changeprofile');


    //レビューページ
    Route::get('/item/{item_id}/review', [ReviewController::class, 'review'])->name('review');
      //レビュー投稿
    Route::post('/item/review/create', [ReviewController::class, 'comment'])->name('review.create');
      //レビュー削除
    Route::post('/item/delete/{comment_id}', [ReviewController::class, 'commentdelete'])->name('review.delete');
    //購入ページ
    Route::get('/purchase/{item_id}', [ItemController::class, 'buypage'])->name('buy');
    Route::post('/purchase/{item_id}/buy', [ItemController::class, 'sold'])->name('sold');
      //支払方法選択->購入ページのinputに送信する
    Route::get('/select/{item_id}/charge', [ItemController::class, 'selectcharge'])->name('selectcharge');
      //決済処理(クレジットカード)
    Route::post('/process-payment', [StripeController::class, 'processPayment'])->name('processPayment');
      //決済処理(コンビニ払い)
    Route::post('/process-convenience-payment', [StripeController::class, 'processConveniencePayment'])->name('processConveniencePayment');
     //決済処理(銀行振込)
    Route::get('/bank-transfer', [StripeController::class, 'bankTransfer'])->name('bankTransfer');
    Route::post('/bank-payment', [StripeController::class, 'processBankPayment'])->name('processBankPayment');

    //住所変更ページ
    Route::get('/purchase/address/{item_id}',[UserController::class, 'address'])->name('address');
        //住所未設定の場合、住所を保存
    Route::post('/purchase/address/{item_id}/store',[UserController::class, 'storeAddress'])->name('storeAddress');
       //変更処理
    Route::post('/purchase/address/{item_id}/change', [UserController::class, 'changeAddress'])->name('changeAddress');
});
