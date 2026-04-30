<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    // ===========================
    // 商品一覧取得
    // ===========================

    /** @test */
    public function 全商品を取得できる()
    {
        /** @var \App\Models\User $user */
    // 商品一覧取得
        $user  = User::factory()->create();
        $other = User::factory()->create();

        Item::factory()->count(3)->create(['user_id' => $other->id, 'status' => 'on_sale', 'name' => 'テスト商品']);

        $response = $this->actingAs($user)->get('/?tab=recommend');

        $response->assertStatus(200);
        $response->assertViewHas('items');
    }

    /** @test */
    public function 購入済み商品にはSoldラベルが表示される()
    {
        /** @var \App\Models\User $user */
        $user  = User::factory()->create();
        $other = User::factory()->create();

        $item = Item::factory()->create(['user_id' => $other->id, 'status' => 'sold_out']);

        $response = $this->actingAs($user)->get('/?tab=recommend');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function 自分が出品した商品は一覧に表示されない()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $myItem    = Item::factory()->create(['user_id' => $user->id, 'name' => '自分の商品']);
        $otherItem = Item::factory()->create(['name' => '他人の商品']);

        $response = $this->actingAs($user)->get('/?tab=recommend');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }

    // ===========================
    // マイリスト一覧取得
    // ===========================

    /** @test */
    public function いいねした商品だけが表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $likedItem  = Item::factory()->create(['name' => 'いいね商品']);
        $otherItem  = Item::factory()->create(['name' => 'いいねしてない商品']);

        Like::create(['user_id' => $user->id, 'item_id' => $likedItem->id]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいね商品');
        $response->assertDontSee('いいねしてない商品');
    }

    /** @test */
    public function マイリストの購入済み商品にはSoldラベルが表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $item = Item::factory()->create(['status' => 'sold_out']);
        Like::create(['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function 未認証の場合マイリストには何も表示されない()
    {
        $otherUser = User::factory()->create();
        Item::factory()->count(3)->create(['user_id' => $otherUser->id]);

        $response = $this->get('/?tab=mylist');

        // 未ログイン時はitemsが空であることを確認
        $items = $response->viewData('items');
        $this->assertEmpty($items);
    }

    // ===========================
    // 商品検索機能
    // ===========================

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        Item::factory()->create(['name' => '腕時計']);
        Item::factory()->create(['name' => 'ノートPC']);

        $response = $this->get('/?search=腕時計');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('ノートPC');
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = Item::factory()->create([
        'user_id' => $otherUser->id,
        'name'    => '腕時計',
        'status'  => 'on_sale',
    ]);
    \App\Models\Like::create([
        'user_id' => $user->id,
        'item_id' => $item->id,
    ]);

        $response = $this->actingAs($user)->get('/?tab=mylist&search=腕時計');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
    }

    // ===========================
    // 商品詳細情報取得
    // ===========================

    /** @test */
    public function 商品詳細ページに必要な情報が表示される()
    {
        $item = Item::factory()->create([
            'name'        => 'テスト商品',
            'bland'       => 'テストブランド',
            'price'       => 5000,
            'description' => 'テスト説明文',
            'condition'   => '良好',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('5,000');
        $response->assertSee('テスト説明文');
        $response->assertSee('良好');
    }
}
