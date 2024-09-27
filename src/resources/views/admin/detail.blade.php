@extends('layouts.guest')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/userdetail.css') }}">
@endsection

@section('main')
<div class="main">
    <div class="admin">
    <a href="{{ route('admin') }}"><button class="back-button"><</button></a>
     @if($user->name == null)
     <h1 class="title">名無未設定ユーザー</h1>
     @else
     <h1 class="title">{{ $user->name }}さん</h1>
     @endif
     
     <div class="all-item-comment">
         <div class="tr-content-01">
           <h2 class="item-title">出品した商品</h2>
            @foreach($user->items as $item)
            <p class="item-title">{{ $item->name }}
            <form action="/admin/item/delete" method="post">
            @csrf
              <div>
                <input type="hidden" name="id" value="{{ $item->id }}">
                <input type="submit" class="item-delete" value="削除" onclick='return confirm("この商品を取り消しますか？")'>
              </div>
            </form>
            </p>
            @endforeach
            
         </div>
        
         <div class="tr-content-01">
            <h2 class="item-title">投稿コメント</h2>
            @foreach($user->comment as $mark)
            <p class="item-title">{{ $mark->comment }}</p>
            <form action="/admin/comment/delete" method="post">
            @csrf
              <div>
                <input type="hidden" name="id" value="{{ $mark->id }}">
                <input type="submit" class="item-delete" value="削除" onclick='return confirm("このコメントを取り消しますか？")'>
              </div>
            </form>
            @endforeach
         </div>
        
        
     </div>
       
    
    </div>
</div>
@endsection