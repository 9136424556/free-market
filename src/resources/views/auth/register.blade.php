@extends('layouts.guest')

@section('main')
<div class="main">
    <div class="main-set">
        <div class="title">
            <h1 class="title-word">会員登録</h1>
        </div>

       
        <div class="content">
        <form class="form" method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            
            <!-- Email Address -->
            <div class="mt-4">
                <p class="mt-label"><x-label  for="email" :value="__('メールアドレス')" /></p>

                <p><x-input id="email" class="input-form" type="email" name="email" :value="old('email')" /></p>
            </div>
            
               @error('email')
                 {{ $message }}
               @enderror
           
         
            <!-- Password -->
            <div class="mt-4">
                <p class="mt-label"><x-label for="password" :value="__('パスワード')" /></p>

                <p><x-input id="password" class="input-form"
                                type="password"
                                name="password"
                                autocomplete="new-password" /></p>
            </div>
            
               @error('password')
                 {{ $message }}
               @enderror

            <div class="mt-4">
                <x-button class="ml-4">
                    {{ __('登録する') }}
                </x-button>
            </div>

                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('ログインはこちら') }}
                </a>

           
        </form>
      </div>
    </div>
</div>
@endsection
