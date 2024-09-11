@extends('layouts.guest')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('main')
<div class="main">
    <div class="admin">
     <h1 class="title">管理者画面</h1>
     
     <table class="all-users">
         <tr class="tr">
           <th class="th">ID</th>
           <th class="th">名前</th>
           <th class="th">メールアドレス</th>
           <th class="th">備考</th>
         </tr>
         @foreach($users as $user)
         <tr class="tr">
           <th>{{ $user->id }}</th>
           <th>{{ $user->name }}</th>
           <th>{{ $user->email }}</th>
           <!--ユーザーを削除-->
           <form action="/admin/delete" method="post">
            @csrf
              <th>
                <input type="hidden" name="id" value="{{ $user->id }}">
                <input type="submit" class="delete" value="削除" onclick='return confirm("このユーザーを取り消しますか？")'>
              </th>
           </form>
         </tr>
         @endforeach
     </table>
       
    
    </div>
</div>
@endsection