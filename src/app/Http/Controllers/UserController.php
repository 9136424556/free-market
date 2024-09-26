<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Sold_item;
use App\Http\Requests\MypageRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function mypage()
    {
        $user = User::find(Auth::id());
        $items = Item::orderBy('id')->get();
         // ログイン中のユーザーが購入した商品を取得
        $solds = Sold_item::where('user_id', $user->id)
        ->with('item')// 関連する商品情報も一緒に取得
        ->get();
       
        return view('mypage',compact('user','items','solds'));
    }

    public function profile()
    {
        $user = User::find(Auth::id());
        $profile = Profile::find(Auth::id());

        return view('profile',compact('user','profile'));
    }

    public function edit($id)
    {
        $user = User::find(Auth::id());
        $profile = Profile::findOrFail($id);

        return view('profile', compact('profile'));
    }

    public function address($item_id)
    {
        $user = User::find(Auth::id());
        $profile = Profile::find(Auth::id());
        $item = Item::find($item_id);

        return view('address',compact('user','profile','item'));
    }
    //プロフィール作成
    public function storeprofile(MypageRequest $request )
    {
        $request->validate([
            'img_url' => 'image|mimes:svg,jpg|max:2048',
        ]);
        //画像を選択した場合のみ起動する処理
        if($request->img_url) {
        //画像をストレージに保存して表示
        $dir = 'image';
        $file_name = $request->file('img_url')->getClientOriginalName();
        $request->file('img_url')->storeAs('public/' . $dir, $file_name);
       
        $user = Auth::user();
        $user->img_url = 'storage/' . $dir . '/' . $file_name;
        $user->save();
        }

            $user = Auth::user();
            $user->name = $request->input('name');
            $user->introduction = $request->input('introduction');
            $user->save();
          
            $input = new Profile();
            $input->postcode = $request->input('postcode');
            $input->address = $request->input('address');
            $input->building = $request->input('building');
            $input->user_id = Auth::id();
            
            $input->save();
        

        return redirect('/mypage');
    }
    //プロフィール変更
    public function changeprofile(Request $request, $id)
    {
        $validatedData = $request->validate([
            'img_url' => 'image|mimes:svg,jpg|max:2048',
            'postcode' => 'required|max:8',
            'address' => 'required|string'
        ]);
        //画像をストレージに保存して表示
        if($request->img_url) {
        $dir = 'image';
        $file_name = $request->file('img_url')->getClientOriginalName();
        $request->file('img_url')->storeAs('public/' . $dir, $file_name);

        $user = Auth::user();
        $user->img_url =  'storage/' . $dir . '/' . $file_name;
        $user->update();
       }
       //名前変更処理
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->introduction = $request->input('introduction');
        $user->update();
       //住所・郵便番号変更処理
       
       $profile =  Profile::findOrFail($id);
       Auth::user()->profile->update($validatedData);
       
        return redirect('/mypage')->with('message','プロフィールを更新しました');
    }

    //購入ページー＞住所変更処理
    public function changeAddress(Request $request ,$item_id)
    {
        $item = Item::find($item_id);
        $change = $request->only('postcode','address','building','user_id');
        Auth::user()->profile->update($change);
       
        return redirect()->route('buy',['item_id' => $item->id ]);
    }
  
    

}
