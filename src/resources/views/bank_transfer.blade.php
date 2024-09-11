@extends('layouts.guest')

@section('main')
<div class="back">
   <a href="{!! '/purchase/' . $item->id  !!}"><button class="back-button"><</button></a>
</div>
 <div class="title">
    <h1>住所の変更</h1>
 </div>


<div class="content">
        <form action="{{ route('storeAddress', ['item_id' => $item->id ]) }}" method="post">
        @csrf
        
        <div class="user-input">
            <p><label for="postnumber">郵便番号</label></p>
            <p><input class="input-form" id="postnumber" name="postcode" type="text" maxlength="8" value="{{ old('postcode') }}"></p>
        </div>
        <div class="user-input">
           <p><label for="address">住所</label></p>
           <p><input class="input-form" id="address" name="address" type="text" value="{{ old('address') }}"></p>
        </div>
        <div class="user-input">
           <p><label for="building">建物名</label></p>
           <p><input class="input-form" id="building" name="building" type="text" value="{{ old('building') }}"></p>
        </div>
        <div class="submit-button">
            <input type="hidden" name="user_id" value="">
            <button class="ml-4" >更新する</button>
        </div>
      </form>
    </div>


@endsection