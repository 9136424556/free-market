@extends('layouts.guest')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('main')
<div class="main">
    <div class="admin">
     <h1 class="title">管理者画面</h1>

     @if(session('message'))
     <div class="alert-success">
        <h4>{{ session('message') }}</h4>
     </div>
     @endif

     @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
     @endif
     
     <table class="all-users">
         <tr class="tr-content">
           <th class="th-content">ID</th>
           <th class="th-content">名前</th>
           <th class="th-content">メールアドレス</th>
           <th class="th-content">商品・投稿コメントの削除</th>
           <th class="th-content">ユーザーを削除</th>
           <th class="th-content">メールを送信</th>
         </tr>
         @foreach($users as $user)
         <tr class="tr-content">
           <td class="th-content">{{ $user->id }}</td>
           <td class="th-content">{{ $user->name }}</td>
           <td class="th-content">{{ $user->email }}</td>
           <td class="th-content"><a href="{!! 'detail/' . $user->id  !!}">詳細ページへ</a></td>
           <!--ユーザーを削除-->
           <td class="th-content">
           <form action="/admin/delete" method="post">
            @csrf
              <div>
                <input type="hidden" name="id" value="{{ $user->id }}">
                <input type="submit" class="delete" value="削除" onclick='return confirm("このユーザーを取り消しますか？")'>
              </div>
           </form>
           </td>
           <!--メールを送信-->
           <td class="th-content">
           <form action="{{ route('sendEmail') }}" method="post">
            @csrf
             <div class="mail-send">
               <div> 
                 <p class="mail"><label for="email">ユーザーのメールアドレス</label></p>
                 <p class="mail"><input class="input-address" type="email" id="email" name="email" value="{{ $user->email }}"  readonly></p>
               </div>
               <div>
                 <p class="mail"><label for="message">メッセージ</label></p>
                 <p class="mail"><textarea class="input-message" id="message" name="message" required></textarea></p>
               </div>
              </div>
              <div>
                 <button class="send" type="submit">送信</button>
              </div>
           </form>
           </td>
         </tr>
         @endforeach
     </table>
       
    
    </div>
</div>
@endsection