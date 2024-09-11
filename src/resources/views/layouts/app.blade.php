<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
        <link rel="stylesheet" href="{{ asset('css/reset.css') }}"/>
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        @yield('css')
       
    </head>

    <body class="">
        <header class="head">
            

            <!-- Page Heading -->
            <div class="header">
                <div class="header-logo">
                    <a href="/"><img class="header-logo-w" src="{{ asset('image/logo.svg') }}" alt=""></a>
                </div>

                <div class="search-form">
                    @yield('search')
                </div>

                @if(Auth::check())
                <div class="port">
                    <form class="link" action="{{ route('logout') }}" name="logout"  method="post">
                        @csrf
                         <p ><a class="link" onclick="document.logout.submit();">ログアウト</a></p>
                    </form>
                </div>
                <div class="port">
                    <p ><a class="link" href="{{ route('mypage') }}">マイページ</a></p>
                </div>
                <!--ユーザーが管理者の場合のみ表示する-->
                @if(isset($user) && $user['role'] == 'admin')
                <div class="port">
                   <p><a class="link" href="{{ route('adminIndex') }}">管理者画面</a></p>
                </div>
                @endif
                <div class="sell-port">
                    <a class="warp-sell" href="/sell">出品</a>
                </div>
                @else <!--ログインしていない場合-->
                <div class="port">
                    <p ><a class="link" href="{{ route('login') }}">ログイン</a></p>
                </div>
                <div class="port">
                    <p ><a class="link" href="{{ route('register') }}">会員登録</a></p>
                </div>
                 <div class="sell-port">
                    <a class="warp-sell" href="{{ route('login') }}">出品</a>
                </div>
                @endif
               
            </div>
          </header>
            <!-- Page Content -->
            <main>
                @yield('main')
            </main>
        
    </body>

</html>
