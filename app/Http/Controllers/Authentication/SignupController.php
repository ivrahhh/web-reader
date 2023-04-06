<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\SignupRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SignupController extends Controller
{
    /**
     * Render the signup page.
     */
    public function create() : Response
    {
        return Inertia::render('Authentication/Signup');
    }

    /**
     * Store the newly created user model.
     */
    public function store(SignupRequest $request, UserRepository $repository) : RedirectResponse
    {
        $user = $repository->createUser(
            credentials: $request->validated(),
        );

        Auth::login($user);

        return redirect('/');
    }
}
