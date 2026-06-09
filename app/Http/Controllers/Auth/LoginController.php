<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirección después del login
     */
    protected function authenticated($request, $user)
    {
        if ($user->hasRole('Contratista')) {
            return redirect()->route('contractors.admin');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}