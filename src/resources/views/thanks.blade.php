@extends('layouts.guest')

@section('main')
<div class="main">
@if($order->status === 'pending')
    <p>現在、支払いが保留されています。しばらくお待ちください。</p>
@elseif($order->status === 'paid')
    <p>支払いが完了しました。商品が発送されます。</p>
@elseif($order->status === 'failed')
    <p>支払いに失敗しました。もう一度お試しください。</p>
@endif
</div>
@endsection