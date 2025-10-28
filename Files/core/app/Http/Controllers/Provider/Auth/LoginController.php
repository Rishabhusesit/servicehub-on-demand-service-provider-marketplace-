<?php

namespace App\Http\Controllers\Provider\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {

    use AuthenticatesUsers;

    public $redirectTo = 'provider';

    public function showLoginForm() {
        $pageTitle = "Provider Login";
        return view('Template::provider.auth.login', compact('pageTitle'));
    }

    public function login(Request $request) {

        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (auth('web')->check()) {
            auth('web')->logout();
        }

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $fieldType = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::guard('provider')->attempt([$fieldType => $credentials['username'], 'password' => $credentials['password']])) {

            $notify[] = ['success', 'Login successfully'];
            return to_route('provider.dashboard')->withNotify($notify);
        }

        $notify[] = ['error', 'Invalid credentials try again'];
        return back()->withNotify($notify);
    }

    public function logout() {
        Auth::guard('provider')->logout();
        $notify[] = ['success', 'Logout successfully'];
        return to_route('provider.login')->withNotify($notify);
    }
}
