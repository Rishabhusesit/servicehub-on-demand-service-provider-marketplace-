<?php

namespace App\Http\Controllers\Provider\Auth;

use App\Http\Controllers\Controller;
use App\Lib\ProviderSocialLogin;

class SocialiteController extends Controller {

    public function socialLogin($provider) {

        $socialLogin = new ProviderSocialLogin($provider);
        return $socialLogin->redirectDriver();
    }

    public function callback($provider) {
        $socialLogin = new ProviderSocialLogin($provider);
        try {
            return $socialLogin->login();
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return to_route('provider.dashboard')->withNotify($notify);
        }
    }
}
