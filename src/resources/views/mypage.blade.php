@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
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
        <div class="user-input-content">
            <div class="icon">
                @if($user->img_url === null)
                <img src="{{ asset('noimage.png') }}" alt="プロフィールアイコン未設定表示" class="profile-icon">
                @else
                <img class="profile-icon" src="{{ asset($user->img_url) }}" alt="プロフィールアイコン">
                @endif
            </div>

            <div class="username-input">
            @if(Auth::check())
            <!--ユーザー名を設定している場合は表示。していなければ「ユーザー名」と表示-->
              @if($user->name)
              <h2>{{ $user->name }}</h2>
              @else
              <h2>ユーザー名</h2>
              @endif
            @endif
           </div>
           
           @if(Auth::check())
           <div class="link-profile">
                <button class="link-button-profile"><a class="page-link-1" href="/mypage/profile">プロフィールを編集</a></button>
           </div>
           @endif

        </div>
       
        <div class="profile-text">
           <p class="introduction">{{ $user->introduction }}</p>
        </div>
       

        <div class="mypage-content">
          <div class="link-tag">
           <input type="radio" name="tab" id="check1" checked><label class="tab" for="check1">出品した商品</label>
           <input type="radio" name="tab" id="check2"><label class="tab" for="check2">購入した商品</label>

             <div class="tab-content" id="tabcontent1">
              <div class="content-1">
              @foreach($user->items as $item)
               <div class="item">
                <div class="item-image">
                    <a href="{!! '/item/' . $item->id !!}"><img class="item-img" src="{{ $item->img_url }}" alt="商品画像"></a>
                </div>
               </div>
              @endforeach
              </div>
             </div>

             <div class="tab-content" id="tabcontent2">
              <div class="content-1">
              @foreach($solds as $sold)
               <div class="item">
                <div class="item-image">                <!--sold_itemsテーブルから購入した商品を表示 ↓-->
                    <a href="{!! '/item/' . $sold->item->id !!}"><img class="item-img" src="{{ $sold->item->img_url }}" alt="商品画像"></a>
                </div>
               </div>
              @endforeach
              </div>
             </div>
           
          </div>
       </div>
</div>
@endsection