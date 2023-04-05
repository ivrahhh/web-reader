<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /**
     * Render the login page.
     */
    public function create() : Response
    {
        return Inertia::render('Authentication/Login');
    }

    /**
     * Authenticates the user.
     */
    public function store(LoginService $service) : RedirectResponse
    {
        $service->authenticate();
        
        return redirect()->intended();
    }
}
