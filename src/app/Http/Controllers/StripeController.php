<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
   
    public function handleWebhook(Request $request)
    {
        // Webhookのペイロードを取得
       $payload = $request->getContent();

       // StripeのWebhookシグネチャを検証（セキュリティ強化）
       $sig_header = $request->header('Stripe-Signature');
       $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

       try {
           // Stripeのイベントを検証
           $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);

           // イベントの種類ごとに処理を分岐
           switch ($event->type) {
               case 'payment_intent.succeeded':
                   $paymentIntent = $event->data->object; // PaymentIntentのオブジェクト
                   Log::info('Payment succeeded for PaymentIntent: ' . $paymentIntent->id);
                   // ここで注文ステータスを更新するなどの処理を追加
                   break;

               case 'payment_intent.payment_failed':
                   $paymentIntent = $event->data->object;
                   Log::error('Payment failed for PaymentIntent: ' . $paymentIntent->id);
                   break;

               // その他のイベントも追加可能
               default:
                   Log::info('Received unknown event type ' . $event->type);
           }
       } catch (\Exception $e) {
           Log::error('Webhook error: ' . $e->getMessage());
           return response()->json(['error' => 'Webhook error'], 400);
       }

       return response()->json(['status' => 'success'], 200);
      
    }

    public function thanks($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('thanks', compact('order'));
    }
}
