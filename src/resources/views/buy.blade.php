@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/buy.css') }}">
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
@if(session('message'))
  <div class="alert-success">
    <h4>{{ session('message') }}</h4>
  </div>
@endif

<div class="three-side">
    <a href="{!! '/item/' . $item->id !!}"><button class="back-button"><</button></a>

    <div id="payment-error" class="alert-danger" style="color: red; display: none;"></div>

    <div class="item-detail">
      <div class="detail-side">
        <div class="item-img">
          <img class="item-photo" src="{{ asset($item->img_url) }}" alt="商品画像">
        </div>

        <div class="item-name">
            <h1>{{ $item->name }}</h1>
            <h3>￥{{ $item->price }}</h3>
        </div>
      </div>

       @php
          $sold = $soldItems->firstWhere('item_id', $item->id);
       @endphp

      @if(!$sold)
      <div class="stripe">
        <div class="how-stripe">
            <div>
               <h1>支払い方法</h1>
            </div>
           <div class="change-cache">
               <a href="#charge"><p >支払方法を変更</p></a>
           </div>
        </div>

        <div class="where">
          <div>
            <h1>配送先</h1>
          </div>
          <div class="change-address">
            @if($profile == null)
            <a href="/mypage/profile">郵便番号・住所を設定</a>
            @else
            <a href="{!! '/purchase/address/' . $item->id !!}"><p>配送先を変更</p></a>
            @endif
          </div>
          
        </div>
        
      </div>
     @endif
    </div>

    <div class="item-confirm">
      <form action="{{ route('sold', ['item_id' => $item->id ]) }}" method="post"  id="payment-form">
      @csrf
      <div class="confirm">
        <div class="tr">
            <label for="price" class="confirm-th">商品代金</label>
            <p class="confirm-td" id="price">{{ $item->price }}</p>
        </div>
        <div class="tr">
            <label for="cashe" class="confirm-th">支払い金額</label>
            <p class="confirm-td" id="cashe">{{ $item->price }}</p>
        </div>
        <div class="tr">
            <label for="selectedPaymentMethod" class="confirm-th">支払い方法</label>   <!-- ↓ モーダルウィンドウで選択した支払方法を表示-->
            <p class="confirm-td"><input class="input-se" type="text" name="payment_method" value="{{ old('payment_method') ?? '' }}" id="selectedPaymentMethod" readonly required></p>
        </div>
      </div>
      
      <div id="card-element" style="display: none;"></div>
            
      <div id="card-errors" role="alert"></div>
      
      <!--エラーメッセージ-->
      @if ($errors->any())
       <div class="error_alert">
         <ul>
          @foreach ($errors->all() as $error)
           <li>{{ $error }}</li>
          @endforeach
         </ul>
       </div>
       @endif
      
      

      <div class="buy-submit">
      @if(!$sold)
        <button id="payment-button" type="submit" class="ml-4">購入する</button>
      @else
        <button class="ml-4" type="button">購入済み</button>
      @endif
      </div>
       
     </form>
    </div>
     
  </div>
</div>
</div>



<!--モーダルウィンドウ　支払方法選択-->
<div class="review-modal" id="charge">
  <div class="modal-log" role="document">
       

     <div class="modal-content" id="paymentModal">
        <div class="modal-head">
         <a href="#" class="modal-close">×</a>
       </div>

       <form action="{{ route('selectcharge', ['item_id'=> $item->id]) }}" method="get">
        @csrf

       <h3>支払方法を選択</h3>

       <div>
        <input type="radio" id="credit_card" name="payment_method" value="card"  {{ old('payment_method','card') == 'card' ? 'checked' : '' }} checked/>
        <label for="credit_card">クレジットカード</label>
       </div>

       <div>
        <input type="radio" id="konbini" name="payment_method" value="konbini"  {{ old('payment_method','konbini') == 'konbini' ? 'checked' : '' }}>
        <label for="konbini">コンビニ払い</label>
       </div>

       <div>
        <input type="radio" id="bank" name="payment_method" value="bank_transfer"  {{ old('payment_method','bank_transfer') == 'bank_transfer' ? 'checked' : '' }}>
        <label for="bank">銀行振込</label>
       </div>

        

        <button class="ml-4" type="submit">決定</button>
      </form>
      </div>

  </div>
</div>


<!--決済処理-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script>
  $(document).ready(function() {
    // モーダルが表示されたときのイベント
   
      // 支払い方法の選択イベント
      $('input[name="payment_method"]').change(function() {
        var creditCardForm = $('#card-element');
        if ($(this).val() === 'card') {
          creditCardForm.show();
        } else {
          creditCardForm.hide();
        }
        // 選択した支払い方法を表示
        $('#selectedPaymentMethod').val($(this).val());
      });

        // 初期状態でクレジットカードが選択されている場合にフォームを表示
    if ($('input[name="payment_method"]:checked').val() === 'card') {
      $('#card-element').show();
    }
   });
</script>
<script>
  //クレジットカード、コンビニ払い、銀行振込の決済処理
      document.addEventListener('DOMContentLoaded', function() {
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements({
          locale: 'ja'  // 日本向けのロケール設定
        });
        var cardElement = elements.create('card',{hidePostalCode: true });
        cardElement.mount('#card-element');

        var form = document.getElementById('payment-form');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Stripeの決済処理開始
            const { error, paymentMethod } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                return;
            }

            // サーバーに送信するためのhidden inputを追加
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method_id');
            hiddenInput.setAttribute('value', paymentMethod.id);
            form.appendChild(hiddenInput);

            // フォームを送信してバックエンドで決済処理を実行
            form.submit();
        });
    });
</script>


@endsection