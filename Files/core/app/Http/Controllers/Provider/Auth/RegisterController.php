<?php

namespace App\Http\Controllers\Provider\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Provider;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    protected function guard()
    {
        return Auth::guard('provider');
    }

    public function showRegistrationForm()
    {
        $pageTitle = "Register";
        return view('Template::provider.auth.register', compact('pageTitle'));
    }

    protected function validator(array $data)
    {

        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $agree = 'nullable';
        if (gs('agree')) {
            $agree = 'required';
        }

        $validate = Validator::make($data, [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|string|email|unique:providers',
            'password'  => ['required', 'confirmed', $passwordValidation],
            'captcha'   => 'sometimes|required',
            'agree'     => $agree,
        ], [
            'firstname.required' => 'The first name field is required',
            'lastname.required'  => 'The last name field is required',
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        if (!gs('registration')) {
            $notify[] = ['error', 'Registration not allowed'];
            return back()->withNotify($notify);
        }
        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        event(new Registered($provider = $this->create($request->all())));

        $this->guard()->login($provider);

        return $this->registered($request, $provider)
        ?: redirect($this->redirectPath());
    }

    protected function create(array $data)
    {
        $referBy = session()->get('reference');
        if ($referBy) {
            $referUser = Provider::where('username', $referBy)->first();
        } else {
            $referUser = null;
        }

        //Provider Create
        $provider            = new Provider();
        $provider->email     = strtolower($data['email']);
        $provider->firstname = $data['firstname'];
        $provider->lastname  = $data['lastname'];
        $provider->password  = Hash::make($data['password']);
        $provider->ref_by    = $referUser ? $referUser->id : 0;
        $provider->ev        = gs('ev') ? Status::NO : Status::YES;
        $provider->sv        = gs('sv') ? Status::NO : Status::YES;
        $provider->kv        = gs('kv') ? Status::NO : Status::YES;
        $provider->ts        = Status::DISABLE;
        $provider->tv        = Status::ENABLE;
        $provider->save();

        $adminNotification              = new AdminNotification();
        $adminNotification->provider_id = $provider->id;
        $adminNotification->title       = 'New member registered';
        $adminNotification->click_url   = urlPath('admin.providers.detail', $provider->id);
        $adminNotification->save();

        //Login Log Create
        $ip            = getRealIP();
        $exist         = UserLogin::where('user_ip', $ip)->where('provider_id', '!=', 0)->first();
        $providerLogin = new UserLogin();

        if ($exist) {
            $providerLogin->longitude    = $exist->longitude;
            $providerLogin->latitude     = $exist->latitude;
            $providerLogin->city         = $exist->city;
            $providerLogin->country_code = $exist->country_code;
            $providerLogin->country      = $exist->country;
        } else {
            $info                        = json_decode(json_encode(getIpInfo()), true);
            $providerLogin->longitude    = isset($info['long']) ? implode(',', $info['long']) : '';
            $providerLogin->latitude     = isset($info['lat']) ? implode(',', $info['lat']) : '';
            $providerLogin->city         = isset($info['city']) ? implode(',', $info['city']) : '';
            $providerLogin->country_code = isset($info['code']) ? implode(',', $info['code']) : '';
            $providerLogin->country      = isset($info['country']) ? implode(',', $info['country']) : '';
        }

        $providerAgent              = osBrowser();
        $providerLogin->provider_id = $provider->id;
        $providerLogin->user_ip     = $ip;

        $providerLogin->browser = isset($providerAgent['browser']) ? $providerAgent['browser'] : '';
        $providerLogin->os      = isset($providerAgent['os_platform']) ? $providerAgent['os_platform'] : '';
        $providerLogin->save();

        return $provider;
    }

    public function checkProvider(Request $request)
    {
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data']  = Provider::where('email', $request->email)->exists();
            $exist['type']  = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data']  = Provider::where('mobile', $request->mobile)->where('dial_code', $request->mobile_code)->exists();
            $exist['type']  = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->username) {
            $exist['data']  = Provider::where('username', $request->username)->exists();
            $exist['type']  = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered()
    {
        return to_route('provider.dashboard');
    }

}
