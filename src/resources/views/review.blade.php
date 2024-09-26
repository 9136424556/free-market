@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review.css') }}">
@endsection

@section('search')
<form class="search-form" action="/search" method="get">
    <div class="search-text">
        <input class="text-field" type="text" name="keyword" placeholder="なにをお探しですか?">
    </div>
</form>
@endsection

@section('main')
<div class="main">
    <div class="back">
      <a href="{!! '/item/' . $item->id !!}"><button class="back-button"><</button></a>
    </div>
    <div class="w-side">
    <div class="item-img">
       <img class="item-photo" src="{{ asset($item->img_url) }}" alt="">
    </div>
    <div class="item-detail">
        <div class="item-name">
            <h1>{{ $item->name }}</h1>
            <h3>￥{{ $item->price }}</h3>
            <div class="review-comment">
               @if($likes->where("user_id", "=", "$user->id")->where("item_id", "=", "$item->id")->first())
               <form action="{{ route('unlike', ['item_id' => $item->id]) }}" method="post">
                @csrf
                  <input class="review-img" type="image" src="{{ asset('image/star2.jpg') }}" alt="お気に入り取り消しアイコン">
               </form>
               @else
               <form action="{{ route('like', ['item_id' => $item->id] ) }}" method="post">
                @csrf
                 <input class="review-img" type="image" src="{{ asset('image/staricon.jpg') }}" alt="お気に入り追加アイコン">
               </form>
               @endif
              <!--レビューアイコン-->
              <img class="review-img" src="{{ asset('image/comment.jpg') }}" alt="レビュー投稿アイコン">
            </div>
            <div class="comment">
                <form class="comment-upload" action="/item/review/create" method="post">
                @csrf
                <div class="comment-input">
                    <p><label for="comment">商品へのコメント</label></p>
                    <p><textarea class="text-input" name="comment" id="comment"></textarea></p>
                </div>
                
                @error('comment')
                {{ $message }}
                @enderror

                <div class="submit-button">
                    <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                    <input type="hidden" name="user_id" value="">
                     <button class="ml-4">コメントを送信する</button>
                </div>
                   
                </form>
               
            </div>
            
        </div>
    </div>
</div>
@endsection