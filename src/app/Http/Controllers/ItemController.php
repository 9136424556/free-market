<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use App\Models\Category;
use App\Models\Category_item;
use App\Models\Comment;
use App\Models\Sold_item;
use App\Http\Requests\BuyRequest;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        $user = User::find(Auth::id());
        $sold = Sold_item::get();

        return view('index', compact('items','user','sold'));
    }
    
    public function detail($item_id, Request $request)
    {
       $item = Item::find($item_id);
       $user = User::find(Auth::id());
       $likes = Like::all();
       $category_item = Category_item::find($item_id);
       $marks = Comment::where('item_id', $item_id)->get();
       //購入済みの場合の処理
       $soldItems = Sold_item::get();
       

       return view('detail', compact('item','user','likes','category_item','marks','soldItems'));
    }

    public function buypage($item_id, Request $request)
    {
        $item = Item::find($item_id);
       
        return view('buy', compact('item'));
    }
  //buypageのinputに選択した支払い方法を送信
    public function selectcharge(Request $request, $item_id)
    {
        $item = Item::find($item_id);
        $sendId = $request->only('payment_method');

        return redirect()->route('buy', ['item_id' => $item->id ])->withInput();
    }

    public function sold(Request $request)
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

        $sold = new Sold_item();
        $sold->user_id = Auth::id();
        $sold->item_id = $request->item_id;
       
        $sold->save();

        return redirect()->route('index');
    }
}
