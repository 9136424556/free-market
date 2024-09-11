<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        //ログイン中のユーザー以外のユーザーを全て取得する
        $users = User::where('id', '!=', Auth::id())->get();

        return view('admin.admin',compact('users'));
    }

    public function userDelete(Request $request)
    {
        User::find($request->id)->delete();

        return redirect('/admin/index');
    }
}
