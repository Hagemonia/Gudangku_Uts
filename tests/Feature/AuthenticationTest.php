<?php

namespace Tests\Feature;

use App\Modules\AuthModule\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get(route('products.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_register_as_staff(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Budi Gudang',
            'email' => 'budi@example.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('users', ['email' => 'budi@example.test', 'role' => 'staff']);
        $this->assertAuthenticated();
    }

    public function test_registration_cannot_assign_admin_role(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Licik',
            'email' => 'licik@example.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'licik@example.test', 'role' => 'staff']);
    }

    public function test_registration_validates_input(): void
    {
        $this->post(route('register.store'), [
            'name' => '',
            'email' => 'bukan-email',
            'password' => 'pendek',
            'password_confirmation' => 'beda',
        ])->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertGuest();
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create(['email' => 'staff@example.test']);

        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'salah'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_sanctum_token_flow(): void
    {
        $user = User::factory()->admin()->create(['email' => 'api@example.test']);

        $token = $this->postJson('/api/auth/login', ['email' => $user->email, 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'role']])
            ->json('token');

        $this->withToken($token)->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('user.role', 'admin');
    }

    public function test_api_me_requires_token(): void
    {
        $this->getJson('/api/auth/me')->assertUnauthorized();
    }
}
