<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCategoryItemsTableForCascadeDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_items', function (Blueprint $table) {
            // 既存の外部キー制約を削除
            $table->dropForeign(['item_id']);
            
            // カスケード削除を設定した外部キー制約を再作成
            $table->foreign('item_id')
                  ->references('id')
                  ->on('items')
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
        Schema::table('category_items', function (Blueprint $table) {
            // カスケード削除を解除し、元の外部キー制約を再追加
            $table->dropForeign(['item_id']);
            $table->foreign('item_id')
                  ->references('id')
                  ->on('items');
        });
    }
}
