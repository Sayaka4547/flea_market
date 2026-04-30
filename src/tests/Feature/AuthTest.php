<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ===========================
    // 会員登録機能
    // ===========================

    /** @test */
    public function 名前が入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name'                  => '',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function メールアドレスが入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => '',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function パスワードが7文字以下の場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function パスワードが確認用パスワードと一致しない場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 全ての項目が入力されている場合会員情報が登録されプロフィール設定画面に遷移する()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $response->assertRedirect('/mypage/profile');
    }

    // ===========================
    // ログイン機能
    // ===========================

    /** @test */
    public function メールアドレスが入力されていない場合ログインバリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email'    => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが入力されていない場合ログインバリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 入力情報が間違っている場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email'    => 'notexist@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function 正しい情報が入力された場合ログイン処理が実行される()
    {
        $user = User::factory()->create([
            'email'    => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    // ===========================
    // ログアウト機能
    // ===========================

    /** @test */
    public function ログアウトができる()
    {
        //$user = User::factory()->create();
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email'    => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }
}
