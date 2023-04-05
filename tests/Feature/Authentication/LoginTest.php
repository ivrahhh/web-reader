<?php

use App\Models\User;

use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\post;

beforeEach(fn () => $this->user = User::factory()->create());

test('user can login', function () {
    $response = post(
        uri: route('authenticate'),
        data: [
            'email' => $this->user->email,
            'password' => 'password',
        ],
    );

    $response->assertValid();
    assertAuthenticatedAs($this->user);
});

test('user cannot login with wrong password', function () {
    $response = post(
        uri: route('authenticate'),
        data: [
            'email' => $this->user->email,
            'password' => 'wrong_password',
        ],
    );

    $response->assertInvalid([
        'password' => trans('auth.password')
    ]);
    assertGuest();
});

test('user cannot login with unregistered email', function () {
    $response = post(
        uri: route('authenticate'),
        data: [
            'email' => 'unregistered@email.com',
            'password' => 'password',
        ],
    );

    $response->assertInvalid([
        'email' => trans('auth.failed'),
    ]);
    assertGuest();
});

test('user cannot login with rate limited', function () {
    foreach(range(1, 6) as $attempts) {
        $response = post(
            uri: route('authenticate'),
            data: [
                'email' => $this->user->email,
                'password' => 'wrong_password',
            ],
        );        
    }

    $response->assertInvalid(['email' => 'Too many login attempts.']);
    assertGuest();
});

it('will throw a validation error', function (string $email, string $password, string $error) {
    $response = post(
        uri: route('authenticate'),
        data: [
            'email' => $email,
            'password' => $password,
        ],
    );

    $response->assertInvalid($error);
    assertGuest();
})->with([
    'invalid email format' => ['invalidEmail', 'password', 'email'],
    'empty email' => ['', 'password', 'email'],
    'empty password' => ['test@email.com', '', 'password'],
]);