<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySoldItemsTableForCascadeDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sold_items', function (Blueprint $table) {
            // 既存の外部キー制約を削除
            $table->dropForeign(['item_id']);
            $table->foreign('item_id')
                  ->references('id')
                  ->on('items')
                  ->onDelete('cascade');  // 親レコード削除時に子レコードも削除

            $table->dropForeign(['user_id']);
            // カスケード削除を設定した外部キー制約を再作成
           
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');  // 親レコード削除時に子レコードも削除
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sold_items', function (Blueprint $table) {
            // カスケード削除を解除し、元の外部キー制約を再追加
            $table->dropForeign(['item_id']);

             $table->foreign('item_id')
                   ->references('id')
                   ->on('items');
                   
            $table->dropForeign(['user_id']);
                  
            $table->foreign('user_id')
                 ->references('id')
                 ->on('users');
        });
    }
}
