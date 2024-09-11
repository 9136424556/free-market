<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('detail');

Route::get('/sell', [SellController::class, 'sell'])->name('sell');
Route::post('/sell/store', [SellController::class, 'itemstore'])->name('display');

Route::prefix('admin')->middleware('verified:admin')->group(function() {
    Route::get('/index', [AdminController::class, 'index'])->name('adminIndex');
    Route::post('/delete',[AdminController::class, 'userDelete']);
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
