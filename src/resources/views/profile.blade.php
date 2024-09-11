@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('search')
<form class="search-form" action="/search" method="get">
    @csrf
    <div class="search-text">
        <input class="text-field" type="text" name="keyword" placeholder="なにをお探しですか?">
    </div>
</form>
@endsection

@section('main')
<div class="main">
  <div class="main-content">
    <div class="title">
        <h1>プロフィール設定</h1>
    </div>

    <div class="content">                  <!--プロフィール更新時のルート : 新規作成時のルート-->
        <form action="{{ $profile ? route('changeprofile',$profile->id) : route('storeprofile') }}" method="post" enctype="multipart/form-data">
        @csrf
        {{-- 更新の場合はPUTメソッドを使う --}}
            @if($profile)
                @method('PUT')
            @endif
        <div class="user-input-image">
            <div class="icon">
              @if($user && $user->img_url)
              <img id="icon_img" name="img_url" class="profile-icon" src="{{ asset($user->img_url ?? '') }}" alt="プロフィール画像" >
              @else
              <img id="icon_img" name="img_url" class="profile-icon" src="{{ asset('noimage.png') }}" alt="プロフィール画像" >
              @endif
            </div>
            <div class="select-photo">
              <input id="icon" class="link-button" name="img_url" multiple="multiple" type="file" onChange="changeImage(event)">
            </div>
        </div>
        <div class="user-input">
            <p><label for="name">ユーザー名</label></p>                             <!--新規作成時の処理 , 変更処理-->
            <p><input class="input-form" id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}"></p>
        </div>
        <div class="user-input">
            <p><label for="postnumber">郵便番号</label></p>
            <p><input class="input-form" id="postnumber" name="postcode" type="text" maxlength="8" value="{{ old('postcode', $user->profile->postcode ?? '') }}"></p>
        </div>
        @error('postcode')
        {{ $message }}
        @enderror
        <div class="user-input">
           <p><label for="address">住所</label></p>
           <p><input class="input-form" id="address" name="address" type="text" value="{{ old('address', $user->profile->address ?? '') }}"></p>
        </div>
        @error('address')
        {{ $message }}
        @enderror
        <div class="user-input">
           <p><label for="building">建物名</label></p>
           <p><input class="input-form" id="building" name="building" type="text" value="{{ old('building', $user->profile->building ?? '') }}"></p>
        </div>
        <div class="submit-button">
            <input type="hidden" name="user_id" value="">
            <button type="submit" class="ml-4">{{ $profile ? '更新する' : '作成する' }}</button>
        </div>
        
      </form>
    </div>

   </div> 
</div>

<!--選択した画像をアイコンに表示する処理-->
<script>
    // アイコン画像プレビュー処理
    // 画像が選択される度に、この中の処理が走る
    $('#icon').on('change', function (ev) {
        // このFileReaderが画像を読み込む上で大切
        const reader = new FileReader();
        // ファイル名を取得
        const fileName = ev.target.files[0].name;
        // 画像が読み込まれた時の動作を記述　#icon_img = img idの"icon_img"のこと
        reader.onload = function (ev) {
            $('#icon_img').attr('src', ev.target.result).css('width', '150px').css('height', '150px');
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
        const imagPreview = $(sectionId).children('#icon_img');
                                                  //↑　#icon_img = img idの"icon_img"のこと
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


</script>
@endsection