<?php

namespace App\Http\Controllers\Provider;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\Intended;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthorizationController extends Controller {
    protected function checkCodeValidity($provider, $addMin = 2) {
        if (!$provider->ver_code_send_at) {
            return false;
        }
        if ($provider->ver_code_send_at->addMinutes($addMin) < Carbon::now()) {
            return false;
        }
        return true;
    }

    public function authorizeForm() {
        $provider = auth('provider')->user();
        if (!$provider->status) {
            $pageTitle = 'Banned';
            $type      = 'ban';
        } else if (!$provider->ev) {
            $type           = 'email';
            $pageTitle      = 'Verify Email';
            $notifyTemplate = 'EVER_CODE';
        } else if (!$provider->sv) {
            $type           = 'sms';
            $pageTitle      = 'Verify Mobile Number';
            $notifyTemplate = 'SVER_CODE';
        } else if (!$provider->tv) {
            $pageTitle = '2FA Verification';
            $type      = '2fa';
        } else {
            return to_route('provider.dashboard');
        }

        if (!$this->checkCodeValidity($provider) && ($type != '2fa') && ($type != 'ban')) {
            $provider->ver_code         = verificationCode(6);
            $provider->ver_code_send_at = Carbon::now();
            $provider->save();
            notify($provider, $notifyTemplate, [
                'code' => $provider->ver_code,
            ], [$type]);
        }

        return view('Template::provider.auth.authorization.' . $type, compact('provider', 'pageTitle'));

    }

    public function sendVerifyCode($type) {
        $provider = auth('provider')->user();

        if ($this->checkCodeValidity($provider)) {
            $targetTime = $provider->ver_code_send_at->addMinutes(2)->timestamp;
            $delay      = $targetTime - time();
            throw ValidationException::withMessages(['resend' => 'Please try after ' . $delay . ' seconds']);
        }

        $provider->ver_code         = verificationCode(6);
        $provider->ver_code_send_at = Carbon::now();
        $provider->save();

        if ($type == 'email') {
            $type           = 'email';
            $notifyTemplate = 'EVER_CODE';
        } else {
            $type           = 'sms';
            $notifyTemplate = 'SVER_CODE';
        }

        notify($provider, $notifyTemplate, [
            'code' => $provider->ver_code,
        ], [$type]);

        $notify[] = ['success', 'Verification code sent successfully'];
        return back()->withNotify($notify);
    }

    public function emailVerification(Request $request) {
        $request->validate([
            'code' => 'required',
        ]);

        $provider = auth('provider')->user();

        if ($provider->ver_code == $request->code) {
            $provider->ev               = Status::VERIFIED;
            $provider->ver_code         = null;
            $provider->ver_code_send_at = null;
            $provider->save();

            $redirection = Intended::getRedirection();
            return $redirection ? $redirection : to_route('provider.dashboard');
        }
        throw ValidationException::withMessages(['code' => 'Verification code didn\'t match!']);
    }

    public function mobileVerification(Request $request) {
        $request->validate([
            'code' => 'required',
        ]);

        $provider = auth('provider')->user();
        if ($provider->ver_code == $request->code) {
            $provider->sv               = Status::VERIFIED;
            $provider->ver_code         = null;
            $provider->ver_code_send_at = null;
            $provider->save();
            $redirection = Intended::getRedirection();
            return $redirection ? $redirection : to_route('provider.dashboard');
        }
        throw ValidationException::withMessages(['code' => 'Verification code didn\'t match!']);
    }

    public function g2faVerification(Request $request) {
        $provider = auth('provider')->user();
        $request->validate([
            'code' => 'required',
        ]);
        $response = verifyG2fa($provider, $request->code);
        if ($response) {
            $redirection = Intended::getRedirection();
            return $redirection ? $redirection : to_route('provider.dashboard');
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }
}
