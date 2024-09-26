<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use App\Models\Item;
use App\Models\Category_item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ItemRequest;

class SellController extends Controller
{
    public function sell()
    {
        $user = Auth::user();
        $conditions = Condition::all();
        $categories = Category::all();

        return view('sell',compact('user','conditions','categories'));
    }

    public function itemstore(ItemRequest $request)
    {
       
        $dir = 'image';
        $file_name = $request->file('img_url')->getClientOriginalName();
        $request->file('img_url')->storeAs('public/' . $dir, $file_name);
        
        $request->validate([
            'img_url' => 'required|image|mimes:jpg,svg|max:2048',
        ]);
        if ($request->hasFile('img_url')) {
            $image = $request->file('img_url');
            $path = $image->store('images/temp', 'public'); // 一時的に画像を保存
    
            // セッションに保存しておく
            session()->flash('uploaded_image', asset('storage/' . $path));
           
        }


        $item = new Item();
        $item->name = $request->input('name');
        $item->price = $request->input('price');
        $item->description = $request->input('description');
        $item->img_url = 'storage/' . $dir . '/' . $file_name;
        $item->user_id = Auth::id();
        $item->condition_id = $request->condition_id;
      
        $item->save();

       $select = new Category_item();
       $select->item_id = $item->id;
       $select->category_id = $request->category;
      
       $select->save();

        return redirect('/');
    }
}
