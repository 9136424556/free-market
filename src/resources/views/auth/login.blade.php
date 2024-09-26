@extends('layouts.guest')

@section('main')
<div class="main">
    <div class="main-set">
        <div class="title">
            <h1>ログイン</h1>
        </div>
            

        <!-- Session Status -->
       

        
        <div class="content">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mt-4">
                <p class="mt-label"><x-label for="email" :value="__('メールアドレス')" /></p>

                <p><x-input id="email" class="input-form" type="email" name="email" :value="old('email')"  autofocus /></p>
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
                                 autocomplete="current-password" /></p>
            </div>
            @error('password')
            {{ $message }}
            @enderror

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('ログインする') }}
                </x-button>
            </div>
                <a class="underline text-sm text-gray-600 hover:text-blue-900" href="{{ route('register') }}">
                    {{ __('会員登録はこちら') }}
                </a>
            
        </form>
      </div>
    </div>
</div>
@endsection
