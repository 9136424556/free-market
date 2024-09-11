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
<div class="three-side">
    <a href="{!! '/item/' . $item->id !!}"><button class="back-button"><</button></a>
    <div class="alert-danger">
        
    </div>

    <div class="item-detail">
      <div class="detail-side">
        <div class="item-img">
          <img class="item-photo" src="{{ asset($item->img_url) }}" alt="">
        </div>

        <div class="item-name">
            <h1>{{ $item->name }}</h1>
            <h3>￥{{ $item->price }}</h3>
        </div>
      </div>

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
            <a href="{!! '/purchase/address/' . $item->id !!}"><p>配送先を変更</p></a>
          </div>
          
        </div>
        
      </div>

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
      
     <div id="creditCardForm" style="display: none;">
            <div class="form-group">
                <label for="cardNumber">カード番号:</label>
                <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456">
            </div>
            <div class="form-group">
                <label for="expiryDate">有効期限:</label>
                <input type="text" class="form-control" id="expiryDate" name="expiryDate" placeholder="MM/YY">
            </div>
            <div class="form-group">
                <label for="cvc">CVC:</label>
                <input type="text" class="form-control" id="cvc" name="cvc" placeholder="123">
            </div>
      </div>
     

      <div class="buy-submit">
        <button type="submit" class="ml-4">購入する</button>
      </div>
       @error('error')
          {{ $message }}
          @enderror
      @error('error')
      {{ $errors->first('payment_method.required') }}
      @enderror
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
        <input type="radio" id="credit_card" name="payment_method" value="クレジットカード"  {{ old('payment_method','クレジットカード') == 'クレジットカード' ? 'checked' : '' }} checked/>
        <label for="credit_card">クレジットカード</label>
       </div>

       <div>
        <input type="radio" id="konbini" name="payment_method" value="コンビニ払い"  {{ old('payment_method','コンビニ払い') == 'コンビニ払い' ? 'checked' : '' }}>
        <label for="konbini">コンビニ払い</label>
       </div>

       <div>
        <input type="radio" id="bank" name="payment_method" value="銀行振込"  {{ old('payment_method','銀行振込') == '銀行振込' ? 'checked' : '' }}>
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  $(document).ready(function() {
    // モーダルが表示されたときのイベント
   
      // 支払い方法の選択イベント
      $('input[name="payment_method"]').change(function() {
        var creditCardForm = $('#creditCardForm');
        if ($(this).val() === 'クレジットカード') {
          creditCardForm.show();
        } else {
          creditCardForm.hide();
        }
        // 選択した支払い方法を表示
        $('#selectedPaymentMethod').val($(this).val());
      });

        // 初期状態でクレジットカードが選択されている場合にフォームを表示
    if ($('input[name="payment_method"]:checked').val() === 'クレジットカード') {
      $('#creditCardForm').show();
    }
   });
</script>


@endsection