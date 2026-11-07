<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Models\Note;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected to login from notes', function () {
    $this->get('/notes')->assertRedirect('/login');
});

test('login and register pages render', function () {
    $this->get('/login')->assertOk()->assertSeeLivewire(Login::class);
    $this->get('/register')->assertOk()->assertSeeLivewire(Register::class);
});

test('users can register', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('notes.index', absolute: false));

    $this->assertAuthenticated();
});

test('users can log in with valid credentials', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('notes.index', absolute: false));

    $this->assertAuthenticatedAs($user);
});

test('users cannot log in with a wrong password', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'wrong')
        ->call('login')
        ->assertHasErrors('email');

    $this->assertGuest();
});

test('users can log out', function () {
    $this->actingAs(User::factory()->create())->post('/logout')->assertRedirect('/');

    $this->assertGuest();
});

test('users only see their own notes', function () {
    $user = User::factory()->create();
    Note::factory()->for($user)->create(['title' => 'Mine']);
    Note::factory()->create(['title' => 'Someone else']);

    $this->actingAs($user)->get('/notes')
        ->assertOk()
        ->assertSee('Mine')
        ->assertDontSee('Someone else');
});

test('created notes belong to the current user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/notes', ['title' => 'Hello', 'body' => 'World'])
        ->assertRedirect('/notes');

    expect($user->notes()->sole()->title)->toBe('Hello');
});
