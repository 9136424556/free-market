<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\PaymentIntent;

class StripeController extends Controller
{
    //クレジットカードでの処理
    public function processPayment(Request $request) 
    {
       Stripe::setApiKey(env('STRIPE_SECRET'));

       $charge = Charge::create([
        'amount' => $request->input('amount') * 100, // 金額を送信
        'currency' => 'jpy',
        'source' => $request->input('stripeToken'), // トークンを使用
        'description' => '購入アイテムの説明',
    ]);
  
    return redirect()->back()->with('success', '支払いが成功しました');
    }

   //銀行振込の場合
    public function bankTransfer(Request $request)
{
    // 振込先の銀行口座情報を表示する
    return view('bank_transfer', ['bankAccount' => $bankAccount]);
}
   // 管理者が入金を確認したら注文を処理する
    public function processBankPayment(Request $request)
    {
        $order = Order::find($request->input('order_id'));
        $order->status = 'paid';
        $order->save();

        return back()->with('success', '銀行振込が確認されました。');
    }

    protected function processConveniencePayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 支払いインテントを作成
        $paymentIntent = PaymentIntent::create([
            'amount' => $request->input('amount') * 100, // 金額を送信
            'currency' => 'jpy',
            'payment_method_types' => ['konbini'], // コンビニ払いを指定
        ]);
    
        // クライアントに支払いインテントの詳細を返す
        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);

        return back()->with('success', 'コンビニ払いの指示を送信しました。');
    }
}
