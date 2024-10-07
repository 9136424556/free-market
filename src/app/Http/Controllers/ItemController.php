<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use App\Models\Profile;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Category_item;
use App\Models\Comment;
use App\Models\Sold_item;
use App\Models\Order;
use App\Http\Requests\BuyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Webhook;
use Stripe\Event;
use Stripe\PaymentIntent;

class ItemController extends Controller
{
    public function index()
    {
       $items = Item::all();
       $user = User::find(Auth::id());
       $sold = Sold_item::get();

       // 各商品が売り切れかどうかのフラグを設定
       foreach ($items as $item) {
         $item->isSoldOut = Sold_item::where('item_id', $item->id)->exists();
       }

       return view('index', compact('items','user','sold'));
    }
    
    public function detail($item_id, Request $request)
    {
       $item = Item::find($item_id);
       $sell = Item::with('user')->findOrFail($item_id);
       $user = User::find(Auth::id());
       $likes = Like::all();
       $category_item = Category_item::find($item_id);
       $marks = Comment::where('item_id', $item_id)->get();
       //購入済みの場合の処理
       $soldItems = Sold_item::get();
       
       return view('detail', compact('item','user','likes','category_item','marks','soldItems','sell'));
    }

    public function buypage($item_id, Request $request)
    {
        $item = Item::find($item_id);
        $user = User::find(Auth::id());
        $soldItems = Sold_item::get();
        $profile = Profile::where('user_id', Auth::id())->first();
        

        return view('buy', compact('item','user','soldItems','profile'));
    }
  //buypageのinputに選択した支払い方法を送信
    public function selectcharge(Request $request, $item_id)
    {
        $item = Item::find($item_id);
        $sendId = $request->only('payment_method');

        return redirect()->route('buy', ['item_id' => $item->id ])->withInput();
    }

    public function sold(Request $request,$item_id)
    {
      // ログイン中のユーザーを取得
        $user = Auth::user();
        // ユーザーの住所、郵便番号を取得
        $postcode = $user->profile->postcode ?? null;
        $address = $user->profile->address ?? null;
  
        // 住所、郵便番号が全て設定されているか確認
        if (empty($address) || empty($postcode)) {
          return redirect()->back()->withErrors(['error' => '購入するには、住所、郵便番号を設定してください。']);
        }

        $request->validate([
            'payment_method' => 'required',
        ], [
            'payment_method.required' => '支払い方法を選択してください。',
        ]);

        $item = Item::findOrFail($item_id);
        
          //支払い方法を取得
        $paymentMethod = $request->input('payment_method_id');
        

        DB::beginTransaction();

        try{

         // Stripe決済処理
         Stripe::setApiKey(env('STRIPE_SECRET'));
    
          switch ($paymentMethod) {
              case 'card':
                  // カード決済
                  $paymentIntent = PaymentIntent::create([
                      'amount' => $item->price * 100,
                      'currency' => 'jpy',
                      'payment_method_types' => [$paymentMethod], // 配列にする
                      'confirmation_method' => 'manual',
                      'confirm' => true,
                  ]);

                 
                  break;

              case 'konbini':
                   // コンビニ決済 (Stripeの例)
                   $paymentIntent = PaymentIntent::create([
                    'amount' => $item->price * 100,
                    'currency' => 'jpy',
                    'payment_method_types' => [$paymentMethod],
                ]);
                break;

              case 'bank_transfer':
                  // 銀行振込
                  $amount = $item->price * 100;
                  $paymentIntent = PaymentIntent::create([
                   'amount' => $amount,
                   'currency' => 'jpy',
                   'payment_method_types' => [$paymentMethod],
                   'payment_method_options' => [
                      'bank_transfer' => [
                          'type' => 'jp_bank_transfer',
                      ],
                  ],
              ]);
              break;

              default:
                    throw new \Exception('無効な支払い方法です。');
          }
       
     if($paymentIntent->status == 'succeeded') {
         $order = new Order();
         $order->item_id = $item->id;
         $order->user_id = auth()->user()->id;
         $order->payment_method = $paymentIntent->id;
         $order->status = 'paid'; // コンビニ払い・銀行振込時は「未決済」として保存
         $order->save();

          //購入処理
         $sold = new Sold_item();
         $sold->user_id = Auth::user()->id;
         $sold->item_id = $item_id;
         $sold->save();

         // 決済成功時にコミット
         DB::commit();

         return redirect()->route('buy', ['item_id' => $item->id])->with('message', '購入が完了しました。');
       } else {
           // 支払いが成功していない場合の処理
          throw new \Exception('決済が完了していません。');
       }
    } catch (\Exception $e) {
          // エラーハンドリング
        \DB::rollBack();
        
        \Log::error('Payment failed: ' . $e->getMessage());
        return redirect()->back()->withErrors('購入に失敗しました。もう一度お試しください。');
    }
       
    }

    public function search(Request $request)
    {
       if($request->has('reset')) {
         return redirect('/')->withInput();
       }
       $keyword = $request->input('keyword');

       $results = Item::where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', "%{$keyword}%") // itemsテーブルのnameカラムを参照
           ->orWhereHas('condition', function ($query) use ($keyword) {
              $query->where('condition','LIKE', "%{$keyword}%");
           })
           ->orWhereHas('categories', function ($query) use ($keyword) {
              $query->where('name','LIKE', "%{$keyword}%" );
           });
        })
       ->get();

           // ログインしているユーザー情報を取得
       $user = Auth::user();


       return view('index',['items' => $results, 'user' => $user]);
    }
}
