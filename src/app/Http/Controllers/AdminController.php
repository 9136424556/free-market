<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotificationMail;

class AdminController extends Controller
{
    public function index()
    {
        //ログイン中のユーザー以外のユーザーを全て取得する
        $users = User::where('id', '!=', Auth::id())->get();

        return view('admin.admin',compact('users'));
    }

    public function detail($id)
    {
        $user = User::find($id);
        $item = Item::all();
        $comments = Comment::where('id', $user->id)->get();
        

        return view('admin.detail',compact('user','comments','item'));
    }
    
    public function userDelete(Request $request)
    {
        User::find($request->id)->delete();

        return redirect('/admin/index');
    }

    public function deleteItem(Request $request)
    {
        Item::find($request->id)->delete();

        return redirect()->back();;
    }

    public function deleteComment(Request $request)
    {
        Comment::find($request->id)->delete();

        return redirect()->back();;
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $users = User::where('id', '!=', Auth::id());
    
       
        $data = [
            'user_name' => 'ユーザー名', 
            'message' => $request->input('message'),
        ];

     try { 
        Mail::to($request->input('email'))->send(new UserNotificationMail($data));

         // 成功時のメッセージをセッションに格納
         return redirect()->back()->with('message', 'メールを送信しました。');
     } catch (\Exception $e) {
        // エラーメッセージをセッションに格納
        return redirect()->back()->with('error', 'メール送信に失敗しました。' . $e->getMessage());
        \Log::error('Email sending failed: ' . $e->getMessage()); // エラーログ
     }

    } 
}
