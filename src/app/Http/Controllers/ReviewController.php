<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function review($item_id)
    {
        $item = Item::find($item_id);
        $likes = Like::all();
        $user = User::find(Auth::id());

        return view('review',compact('item','likes','user'));
    }

    public function comment(ReviewRequest $request)
    {
        $review = new Comment;
        $review->item_id = $request->item_id;
        $review->user_id = Auth::id();
        $review->comment = $request->comment;
        
        $review->save();

        return redirect()->route('detail', ['item_id' => $review->item_id])->withInput();
    }

    public function commentdelete($comment_id)
    {
        Comment::find($comment_id)->delete();

        return back();
    }
}
