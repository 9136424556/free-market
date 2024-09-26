@extends('layouts.guest')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('main')
<div class="main">
    <div class="title">
        <h1>商品の出品</h1>
    </div>

    <div class="content">
        <form class="sell-form" action="{{ route('display') }}" method="post" enctype="multipart/form-data">
        @csrf
        <!--商品画像をアップロード-->
        <div class="user-input-image">
            <img id="item_img" class="item-photo" src="{{ asset('gray.png') }}" alt="商品画像">
            <input id="item" class="link-button" name="img_url" multiple="multiple" type="file" value="{{ old('img_url')}}" onChange="changeImage(event)" accept="image/*">
        </div>
        @error('img_url')
        {{ $message }}
        @enderror
       

        <div class="input-colum">
            <h4 class="input-title-logo">商品の詳細</h4>
        </div>
        <div class="user-input">
            <p><label for="category" class="content-ex">カテゴリー</label></p>
            <select  class="sell-input-form1" id="category" name="category" >
                <option value="" hidden>該当するカテゴリーを選択してください</option>
                @foreach($categories as $category)
                <option value="{{ $category['id'] }}" {{ old('category') == $category['id'] ? 'selected' : ''}}>{{ $category['name'] }}</option>
                @endforeach
            </select>
        </div>
        @error('category')
        {{ $message }}
        @enderror
        <div class="user-input">
            <p><label class="content-ex" for="condition">商品の状態</label></p>
            <select class="sell-input-form" id="condition" name="condition_id" type="text" value="">
                <option value="" hidden>該当する状態を選択してください</option>
                @foreach($conditions as $condition)
                <option value="{{ $condition['id'] }}" {{ old('condition_id') == $condition['id'] ? 'selected' : ''}} >{{ $condition['condition'] }}</option>
                @endforeach
            </select>
        </div>
        @error('condition_id')
        {{ $message }}
        @enderror
        <div class="input-colum">
            <h4 class="input-title-logo">商品名と説明</h4>
        </div>
        <div class="user-input">
            <p><label class="content-ex" for="name">商品名</label></p>
            <p><input class="sell-input-form" id="name" name="name" type="text" value="{{ old('name') }}"></p>
        </div>
        @error('name')
        {{ $message }}
        @enderror
        <div class="user-input">
           <p><label class="content-ex" for="description">商品の説明</label></p>
           <p><textarea class="sell-input-form" id="description" name="description" type="text" >{{ old('description') }}</textarea></p>
        </div>
        @error('description')
        {{ $message }}
        @enderror
        <div class="input-colum">
            <h4 class="input-title-logo">販売価格</h4>
        </div>
        <div class="user-input">
           <p><label class="content-ex" for="price">販売価格</label></p>
           <p>￥<input class="sell-input-form" id="price" name="price" type="text" pattern="^[+-]?([1-9][0-9]*|0)$" value="{{ old('price') }}"></p>
        </div>
        @error('price')
        {{ $message }}
        @enderror

        <div class="submit-button">
            <input type="hidden" name="user_id" value="">
            <button class="ml-5">出品する</button>
        </div>
        @if ($errors->has('error'))
           <div class="alert alert-danger">
              {{ $errors->first('error') }}
           </div>
         @endif
      </form>
   </div>
 </div>

 <!--選択した画像をアイコンに表示する処理-->
<script>
    // アイコン画像プレビュー処理
    // 画像が選択される度に、この中の処理が走る
    $('#item').on('change', function (ev) {
        // このFileReaderが画像を読み込む上で大切
        const reader = new FileReader();
        // ファイル名を取得
        const fileName = ev.target.files[0].name;
        // 画像が読み込まれた時の動作を記述　#icon_img = img idの"icon_img"のこと
        reader.onload = function (ev) {
            $('#item_img').attr('src', ev.target.result).css('width', '150px').css('height', '150px');
        }
        reader.readAsDataURL(this.files[0]);
    })

    function changeImage(event) {
        //FileReaderを使用することでユーザーのコンピュータに保存されているファイルの内容を非同期に読み取ることができる。
        const reader = new FileReader();

        // 新規ファイルデータを取得
        const fileData = event.target.files[0];
        //↓ちゃんと取れていればOK
        console.log(fileData);

        // 今回は一つの記事に対して複数のセクションがあるため画像を表示させる要素を取得する必要がある
        let sectionId = this.event.path[1];
        const imagPreview = $(sectionId).children('#item_img');
                                                  //↑　#item_img = img idの"item_img"のこと
        // 画像を表示させる処理
        // onloadは、データの読み込みが正常に完了した時に発火する
        reader.onload = function (event) {
            $(imagPreview).attr('src', event.target.result).css('width', '150px').css('height', '150px');
        }

        // 読み込みを実行
        reader.readAsDataURL(event.target.files[0]);

        //sendImagePath関数に引数として新規ファイルデータ,画像を表示させる要素を渡す
        sendimg_url(fileData,sectionId);
    }
    document.getElementById('item').onchange = function (evt) {
    var tgt = evt.target || window.event.srcElement,
        files = tgt.files;

    // FileReaderがサポートされているか確認
    if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function () {
            document.getElementById('preview').src = fr.result;
        }
        fr.readAsDataURL(files[0]);
      }
    }

</script>
 @endsection