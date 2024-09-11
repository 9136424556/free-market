<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyProfilesTableForCascadeDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles',function (Blueprint $table) {
            // 既存の外部キー制約を削除
            $table->dropForeign(['user_id']);
             // カスケード削除を設定した外部キー制約を再作成
             $table->foreign('user_id')
             ->references('id')
             ->on('users')
             ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles',function (Blueprint $table) {
             // カスケード削除を解除し、元の外部キー制約を再作成
             $table->dropForeign(['user_id']);
             $table->foreign('user_id')
                   ->references('id')
                   ->on('users');
        });
    }
}
