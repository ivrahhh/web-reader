<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Auth\Events\Registered;

final class UserRepository
{
    /**
     * Create new user.
     */
    public function createUser(array $credentials) : User
    {
        $userData = array_merge($credentials, [
            'username' => $this->generateTemporaryUsername(),
        ]);

        $user = User::query()->create(
            attributes: $userData
        );

        event(new Registered($user));

        return $user;
    }

    /**
     * Generate a temporary username for the user account.
     */
    public function generateTemporaryUsername() : string
    {
        return fake()->unique()->userName();
    }
}