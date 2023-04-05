<?php

namespace App\Services;

use App\Http\Requests\Authentication\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginService
{
    /**
     * The instance of `Authenticatable`.
     */
    public Authenticatable $user;

    public function __construct(
        private LoginRequest $request
    ) {}

    /**
     * Attempt to authenticate the user.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(?string $guard = null) : void
    {
        $this->checkRateLimit();

        if (!$this->attempt()) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => trans('auth.password'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        Auth::guard($guard)->login(
            user: $this->getAuthenticatable(),
            remember: $this->remember(),
        );
    }

    /**
     * Check if the user credentials are valid.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function attempt() : bool
    {
        $user = User::query()->where('email', $this->credentials('email'))
            ->first(['id','password']);

        if (is_null($user)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $this->setAuthenticatable($user);

        return Hash::check(
            value: $this->credentials('password'),
            hashedValue: $user->password,
        );
    }

    /**
     * Check if the request is rate limited.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function checkRateLimit() : void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            event(new Lockout($this->getRequest()));

            throw ValidationException::withMessages([
                'email' => trans('auth.failed', [
                    'seconds' => RateLimiter::availableIn($this->throttleKey()),
                ])
            ]);
        }
    }

    /**
     * Get the rate limiting throttle key.
     */
    protected function throttleKey() : string
    {
        return Str::transliterate(
            string: $this->getRequest()->ip().':'.Str::lower($this->credentials('email'))
        );
    }

    /**
     * Get the instance of the request.
     */
    public function getRequest() : LoginRequest
    {
        return $this->request;
    }

    /**
     * Get the user credentials.
     */
    protected function credentials(?string $key = null) : mixed
    {
        return $this->getRequest()->validated($key);
    }

    /**
     * Check if the user will be remember.
     */
    protected function remember() : bool
    {
        if (! $this->getRequest()->has('remember')) {
            return false;
        }

        return $this->getRequest()->boolean('remember');
    }

    /**
     * Get the instance of `Authenticatable`.
     */
    public function getAuthenticatable() : Authenticatable
    {
        return $this->user;
    }

    /**
     * Set the value of `Authenticatable`.
     */
    public function setAuthenticatable(Authenticatable $user) : void
    {
        $this->user = $user;
    }
}