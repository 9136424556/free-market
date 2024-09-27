<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;

class ItemStoreTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();

        // データベースシーディングを実行
        $this->seed();
    }
    /** @test */
    public function it_stores_a_new_item_with_image_and_category()
    {
        // テスト用のストレージをモック
        Storage::fake('public');

        // テスト用のユーザーを作成し、ログイン状態にする
        $user = User::factory()->create();
        $this->actingAs($user);

        // テスト用のカテゴリと状態を作成
        $category = Category::factory()->create();
        $condition = Condition::factory()->create();

        // テスト用の画像ファイルを作成
        $file = UploadedFile::fake()->image('test_image.jpg');

        // フォームデータを作成
        $formData = [
            'name' => 'Test Item',
            'price' => 1000,
            'description' => 'This is a test description.',
            'img_url' => $file, // 画像をアップロード
            'condition_id' => $condition->id,
            'category' => $category->id,
        ];

        // POSTリクエストを送信
        $response = $this->post('/item/store', $formData);

        // 正しいリダイレクトが行われているか確認
        $response->assertRedirect('/');

        // 画像が正しいディレクトリに保存されたか確認
        Storage::disk('public')->assertExists('image/test_image.jpg');

        // アイテムがデータベースに正しく保存されているか確認
        $this->assertDatabaseHas('items', [
            'name' => 'Test Item',
            'price' => 1000,
            'description' => 'This is a test description.',
            'user_id' => $user->id, // ログインしていたユーザーID
            'img_url' => 'storage/image/test_image.jpg',
            'condition_id' => $condition->id,
        ]);

        // カテゴリとアイテムの関連が正しく保存されているか確認
        $this->assertDatabaseHas('category_items', [
            'item_id' => Item::first()->id,
            'category_id' => $category->id,
        ]);
    }
}