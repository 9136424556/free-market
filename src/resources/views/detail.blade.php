@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
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
  <div class="w-side">
    <div class="back">
    @if (request()->headers->get('referer') && strpos(request()->headers->get('referer'), 'mypage') !== false)
    <!-- mypage.blade.phpからのアクセスの場合 -->
     <a href="/mypage"><button class="back-button" type="button"><</button></a>
    @else
    <!-- index.blade.phpからのアクセスの場合 -->
     <a href="/"><button class="back-button" type="button"><</button></a>
    @endif
    </div>

   

    <div class="item-img">
       <img class="item-photo" src="{{ asset($item->img_url) }}" alt="商品画像">
    </div>
    <div class="item-detail">
        <div class="item-name">
            <h1>{{ $item->name }}</h1>
            <h3>￥{{ $item->price }}</h3>
            <div class="review-comment">
              @if(Auth::check())
              <!--ログインしているユーザーがお気に入り追加している場合は★マークを黄色に、していなければそのまま-->
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
              @else
              <img class="review-img" src="{{ asset('image/staricon.jpg') }}" alt="お気に入り追加アイコン">
              @endif
              <!--レビューアイコン-->
              <a href="#review" type="button" ><img class="review-img" src="{{ asset('image/comment.jpg') }}" alt="レビュー投稿アイコン"></a>
            </div>
           
            
            @php
              $sold = $soldItems->firstWhere('item_id', $item->id);
            @endphp
          
           @if(Auth::check())
            @if(!$sold && $item->user_id == $user->id) <!--自分が出品した商品の場合、購入ページに移動させない-->
            <button class="ml-4">出品した商品のため購入できません</button>
            @elseif(!$sold) <!-- 商品が購入されていない場合の処理 -->
            <a href="{!! '/purchase/' . $item->id  !!}"><button class="ml-4">購入する</button></a>
            @elseif($sold && $sold->user_id == $user->id)  <!--商品を購入したユーザーの場合-->
            <a href="{!! '/purchase/' . $item->id  !!}"><button class="ml-4">購入詳細</button></a>
            @else  <!--商品が購入済みの場合-->
            <button class="ml-4">購入済み</button>
            @endif
            @endif

            @if(!Auth::check())
            <a href="{!! '/purchase/' . $item->id  !!}"><button class="ml-4">購入するにはログインする必要があります</button></a>
            @endif
            
        </div>
        <div class="item-description">
          <h1>商品説明</h1>
          <p>{{ $item->description }}</p>
        </div>
        <div class="item-other">
          <h1>商品の情報</h1>
          <div class="item-condition">
            <h4>商品の状態</h4>
            <p class="it-other-1">{{ $item->condition->condition }}</p>
          </div>
          <div class="item-condition">
            <h4>カテゴリー</h4>
            <p class="it-other-1">{{ $category_item->category->name }}</p>
          </div>
        </div>
        <div class="item-other">
          <h1>出品者情報</h1>
          <div class="item-condition">
            @if($sell->user->name == null)
            <h4 class="it-other-1">名無しのユーザー</h4>
            @else
            <h4 class="it-other-1">{{ $sell->user->name }}</h4>
            @endif
          </div>
          <div class="item-condition">
            <p class="it-other-1">{{ $sell->user->introduction }}</p>
          </div>
          
        </div>
    </div>
  </div>
</div>

<div class="review-modal" id="review">
  <div class="modal-log" role="document">
    <div class="modal-content">

       <div class="modal-head">
         <a href="#" class="modal-close">×</a>
       </div>
       <div class="review-upload">
        @if(Auth::check())
        <!--コメントを投稿済みの場合は「投稿済み」、投稿していない場合は「レビュー投稿ページ」へ遷移するボタンを表示-->
        @if($marks->where('item_id', $item->id)->where('user_id', Auth::id())->first())
        <button class="mt-3">レビュー投稿済み</button>
        @else
         <a href="{!! '/item/' . $item->id . '/review' !!}"><button class="mt-2">レビューを投稿</button></a>
         @endif
         @endif
       </div>

       <div class="modal-body">
             @if($marks->isEmpty()) <!--レビュー投稿がまだない場合-->
                  <p class="review-none">このお店のレビューはまだありません</p>
             @else
                  @foreach($marks as $mark)
                    @if($mark->user->name == null) <!--名前が未設定の場合-->
                    <p>名前未設定ユーザー</p>
                    @else
                    <p>{{ $mark->user->name }}</p>
                    @endif
                    <div class="review-comment">
                        <p>{{ $mark['comment']}}</p>
                    </div>
                    <div class="comment-delete">
                      @if(Auth::check())
                      <!--自分が投稿したコメントのみ削除可能-->
                      @if($mark->user_id == Auth::id())
                      <form action="{{ route('review.delete', ['comment_id' => $mark->id] ) }}" method="post">
                        @csrf
                        <input type="submit" class="delete" value="削除する" onclick='return confirm("コメントを取り消しますか？")'>
                      </form>
                      @endif
                      @endif
                    </div>
                  @endforeach
             @endif
       </div>
    </div>
  </div>
</div>

@endsection