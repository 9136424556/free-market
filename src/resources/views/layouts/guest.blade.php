<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="{{ asset('css/reset.css') }}"/>
        <link rel="stylesheet" href="{{ asset('css/guest.css') }}">
        <link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        @yield('css')
    </head>

    <body>
      <header class="head">
        <div class="header">
                <div class="header-logo">
                    <a href="/"><img src="{{ asset('image/logo.svg') }}" alt=""></a>
                </div>

                <div class="search-form">
                    @yield('search')
                </div>
                
         </div>
      </header>
          <main>
              @yield('main')
          </main>
   
    </body>
    
</html>
