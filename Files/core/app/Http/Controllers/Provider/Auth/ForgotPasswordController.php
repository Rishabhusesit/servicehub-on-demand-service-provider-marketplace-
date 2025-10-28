<?php

namespace App\Http\Controllers\Provider\Auth;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\ProviderPasswordReset;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller {
    public function showLinkRequestForm() {
        $pageTitle = "Account Recovery";
        return view('Template::provider.auth.passwords.email', compact('pageTitle'));
    }

    public function sendResetCodeEmail(Request $request) {

        $request->validate([
            'value' => 'required',
        ]);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $fieldType = $this->findFieldType();
        $user      = Provider::where($fieldType, $request->value)->first();

        if (!$user) {
            $notify[] = ['error', 'The account could not be found'];
            return back()->withNotify($notify);
        }

        ProviderPasswordReset::where('email', $user->email)->delete();
        $code                 = verificationCode(6);
        $password             = new ProviderPasswordReset();
        $password->email      = $user->email;
        $password->token      = $code;
        $password->created_at = \Carbon\Carbon::now();
        $password->save();

        $userIpInfo      = getIpInfo();
        $userBrowserInfo = osBrowser();
        notify($user, 'PASS_RESET_CODE', [
            'code'             => $code,
            'operating_system' => isset($userBrowserInfo['os_platform']) ? $userBrowserInfo['os_platform'] : '',
            'browser'          => isset($userBrowserInfo['browser']) ? $userBrowserInfo['browser'] : '',
            'ip'               => isset($userIpInfo['ip']) ? $userIpInfo['ip'] : '',
            'time'             => isset($userIpInfo['time']) ? $userIpInfo['time'] : '',
        ], ['email']);

        $email = $user->email;
        session()->put('pass_res_mail', $email);
        $notify[] = ['success', 'Password reset email sent successfully'];
        return to_route('provider.password.code.verify')->withNotify($notify);
    }

    public function findFieldType() {
        $input = request()->input('value');

        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $input]);
        return $fieldType;
    }

    public function codeVerify(Request $request) {
        $pageTitle = 'Verify Email';
        $email     = $request->session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error', 'Oops! session expired'];
            return to_route('provider.password.request')->withNotify($notify);
        }
        return view('Template::provider.auth.passwords.code_verify', compact('pageTitle', 'email'));
    }

    public function verifyCode(Request $request) {
        $request->validate([
            'code'  => 'required',
            'email' => 'required',
        ]);
        $code = str_replace(' ', '', $request->code);

        if (ProviderPasswordReset::where('token', $code)->where('email', $request->email)->count() != 1) {
            $notify[] = ['error', 'Verification code doesn\'t match'];
            return to_route('provider.password.request')->withNotify($notify);
        }
        $notify[] = ['success', 'You can change your password'];
        session()->flash('fpass_email', $request->email);
        return to_route('provider.password.reset', $code)->withNotify($notify);
    }
}
