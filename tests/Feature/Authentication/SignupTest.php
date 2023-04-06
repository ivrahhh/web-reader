<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

beforeEach(fn () => $this->userData = [
    'email' => 'test@example.com',
    'password' => 'password',
    'password_confirmation' => 'password',
]);

test('user can create new account', function () {
    $response = post(
        uri: route('signup.store'),
        data: $this->userData,
    );

    $response->assertValid();
    assertDatabaseHas(User::class, [
        'email' => $this->userData['email'],
    ]);
});

it('will dispatched ['.Registered::class.'] event after successful registration', function () {
    Event::fake(Registered::class);
    
    $response = post(
        uri: route('signup.store'),
        data: $this->userData,
    );

    $response->assertValid();
    Event::assertDispatched(Registered::class);
});

it('will authenticate the user after successful registration', function () {
    $response = post(
        uri: route('signup.store'),
        data: $this->userData,
    );

    $response->assertValid();
    assertAuthenticated();
});

it('will throw a validation error if the email is already used', function () {
    $user = User::factory()->create();
    $userData = array_merge($this->userData, [
        'email' => $user->email,
    ]);

    $response = post(
        uri: route('signup.store'),
        data: $userData,
    );

    $response->assertInvalid(['email' => 'has already been taken.']);
});

it('will throw a validation error', function (array $userData, string $errorBag) {
    $response = post(
        uri: route('signup.store'),
        data: $userData
    );

    $response->assertInvalid($errorBag);
})->with([
    'empty email' => fn () => [Arr::except($this->userData, 'email'), 'email'],
    'empty password' => fn () => [Arr::except($this->userData, 'password'), 'password'],
    'invalid email' => fn () => [array_merge($this->userData, ['email' => 'invalidEmail']), 'email'],
    'less than 8 characters password' => fn () => [array_merge($this->userData, ['password' => 'pass']), 'password'],
    'password not confirmed' => fn () => [Arr::except($this->userData, 'password_confirmation'), 'password'],
]);