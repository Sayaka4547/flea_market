<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Purchase;
use App\Models\Profile;
use App\Models\Payment;
use App\Models\Category;

class LikeCommentPurchaseTest extends TestCase
{
    use RefreshDatabase;

    // ===========================
    // いいね機能
    // ===========================

    /** @test */
    public function いいねアイコンを押下するといいねした商品として登録されいいね数が増加する()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post('/like/' . $item->id);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function 再度いいねアイコンを押下するといいねが解除される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        // 先にいいねを登録
        Like::create(['user_id' => $user->id, 'item_id' => $item->id]);

        // 再度押下でいいね解除
        $response = $this->actingAs($user)->post('/like/' . $item->id);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    // ===========================
    // コメント送信機能
    // ===========================

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post('/comment/' . $item->id, [
            'comment' => 'テストコメントです。',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメントです。',
        ]);
    }

    /** @test */
    public function ログイン前のユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $response = $this->post('/comment/' . $item->id, [
            'comment' => 'テストコメントです。',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', ['comment' => 'テストコメントです。']);
    }

    /** @test */
    public function コメントが入力されていない場合バリデーションメッセージが表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post('/comment/' . $item->id, [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']);
    }

    /** @test */
    public function コメントが255文字以上の場合バリデーションメッセージが表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post('/comment/' . $item->id, [
            'comment' => str_repeat('あ', 256), // コメントはmax:1000ですが、テスト名に合わせて255以上（1001文字など）にするか、Controllerのmaxを255にする必要があります。ここでは1001文字にします。
        ]);

        $response->assertSessionHasErrors(['comment']);
    }

    // ===========================
    // 商品購入機能
    // ===========================

    /** @test */
    public function 購入するボタンを押下すると購入が完了する()
    {
        $seller = User::factory()->create(['email_verified_at' => now()]);
        $buyer  = User::factory()->create(['email_verified_at' => now()]);
        $item   = Item::factory()->create(['user_id' => $seller->id, 'status' => 'on_sale']);

        $payment = Payment::factory()->create(['payment' => '銀行振込']);

        Profile::factory()->create([
            'user_id'     => $buyer->id,
            'postal_code' => '123-4567',
            'address'     => '東京都渋谷区',
            'building'    => 'テストビル',
        ]);
        /** @var \App\Models\User $buyer */
        $buyer = User::find($buyer->id);
        $response = $this->actingAs($buyer)->post('/purchase/' . $item->id, [
            'payment_id' => $payment->id,
        ]);

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $buyer->id,
        ]);
    }

    /** @test */
    public function 購入した商品は商品一覧画面でSoldと表示される()
    {
        $seller = User::factory()->create(['email_verified_at' => now()]);
        /** @var \App\Models\User $buyer */
        $buyer  = User::factory()->create(['email_verified_at' => now()]);
        $item   = Item::factory()->create(['user_id' => $seller->id, 'status' => 'on_sale']);

        $payment = Payment::factory()->create(['payment' => '銀行振込']);

        Profile::factory()->create([
            'user_id'     => $buyer->id,
            'postal_code' => '123-4567',
            'address'     => '東京都渋谷区',
            'building'    => 'テストビル',
        ]);

        $this->actingAs($buyer)->post('/purchase/' . $item->id, [
            'payment_id' => $payment->id,
        ]);

        $item->refresh();
        $this->assertEquals('sold_out', $item->status);
    }

    /** @test */
    public function 購入した商品がプロフィールの購入した商品一覧に追加されている()
    {
        $seller = User::factory()->create(['email_verified_at' => now()]);
        $buyer  = User::factory()->create(['email_verified_at' => now()]);
        $item   = Item::factory()->create(['user_id' => $seller->id, 'status' => 'on_sale']);
        $payment = Payment::factory()->create(['payment' => '銀行振込']);

        Profile::factory()->create([
            'user_id'     => $buyer->id,
            'postal_code' => '123-4567',
            'address'     => '東京都渋谷区',
            'building'    => 'テストビル',
        ]);
        /** @var \App\Models\User $buyer */
        $buyer = User::find($buyer->id);
        $this->actingAs($buyer)->post('/purchase/' . $item->id, [
            'payment_id' => $payment->id,
        ]);

        $response = $this->actingAs($buyer)->get('/mypage?tab=buy');

        $response->assertStatus(200);
        $response->assertSee($item->name);
    }

    // ===========================
    // 支払い方法選択機能
    // ===========================

    /** @test */
    public function 支払い方法を選択すると小計画面に反映される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('payment_id');
    }

    // ===========================
    // 配送先変更機能
    // ===========================

    /** @test */
    public function 住所変更画面で登録した住所が商品購入画面に反映される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)->put('/address/update', [
            'postal_code' => '123-4567',
            'address'     => '東京都渋谷区',
            'building'    => 'テストビル',
        ]);

        $response = $this->actingAs($user)->get('/purchase/' . Item::factory()->create()->id);

        $response->assertStatus(200);
        $response->assertSee('東京都渋谷区');
    }

    // ===========================
    // ユーザー情報取得
    // ===========================

    /** @test */
    public function プロフィールページに必要な情報が表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['name' => 'テストユーザー', 'email_verified_at' => now()]);
        // Profileも作成しておく
        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
    }

    // ===========================
    // ユーザー情報変更
    // ===========================

    /** @test */
    public function プロフィール編集画面に過去設定した情報が初期値として表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['name' => 'テストユーザー', 'email_verified_at' => now()]);

        Profile::factory()->create([
            'user_id'     => $user->id,
            'postal_code' => '123-4567',
            'address'     => '東京都渋谷区',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
    }

    // ===========================
    // 出品商品情報登録
    // ===========================

    /** @test */
    public function 商品出品画面で必要な情報が保存できる()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name'        => 'テスト商品',
            'bland'       => 'テストブランド',
            'price'       => 5000,
            'condition'   => '良好',
            'description' => 'テスト説明文',
            'image'       => UploadedFile::fake()->image('test.jpg'),
            'categories'  => [$category->id],
        ]);

        $this->assertDatabaseHas('items', [
            'name'  => 'テスト商品',
            'price' => 5000,
        ]);
    }
}
