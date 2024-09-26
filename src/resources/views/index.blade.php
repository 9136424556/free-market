@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('search')
<form class="search-form" action="/search" method="get">
    <div class="search-text">
        <input class="text-field" type="text" name="keyword" placeholder="なにをお探しですか?">
        <button class="search-button" type="submit">検索</button>
    </div>
</form>
@endsection

@section('main')
<div class="link-tag">
        <input type="radio" name="tab" id="check1" checked><label class="tab" for="check1">おすすめ</label>
        <input type="radio" name="tab" id="check2"><label class="tab" for="check2">マイリスト</label>
        <div class="tab-content" id="tabcontent1">
          <div class="main">
          @foreach($items as $item)
          <div class="item">
             <div class="item-image">
               <!--購入済みの商品の場合に表示-->
                @if($item->isSoldOut)
                  <h4 style="color:red">sold out</h4>
                @endif
                <a href="{!! '/item/' . $item->id !!}"><img class="item-img" src="{{ $item->img_url }}"  alt="商品画像"></a>
             </div>
          </div>
          @endforeach
          </div>
        </div>

        <div class="tab-content" id="tabcontent2">
        @if(Auth::check())
        <div class="main">
          @foreach($user->likes as $item)
          <div class="item">
             <div class="item-image">
                <a href="{!! '/item/' . $item->id !!}"><img class="item-img" src="{{ $item->img_url }}" alt="商品画像"></a>
                
             </div>
          </div>
          @endforeach
          </div>
        @endif
        </div>
</div>

@endsection