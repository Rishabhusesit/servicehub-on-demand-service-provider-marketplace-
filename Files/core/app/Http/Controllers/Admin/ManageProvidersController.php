<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\Provider;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ManageProvidersController extends Controller {

    public function allProviders() {
        $pageTitle = 'All Providers';
        $providers = $this->providerData();
        return view('admin.providers.list', compact('pageTitle', 'providers'));
    }

    public function activeProviders() {
        $pageTitle = 'Active Providers';
        $providers = $this->providerData('active');
        return view('admin.providers.list', compact('pageTitle', 'providers'));
    }

    public function bannedProviders() {
        $pageTitle = 'Banned Providers';
        $providers = $this->providerData('banned');
        return view('admin.providers.list', compact('pageTitle', 'providers'));
    }

    public function emailUnverifiedProviders() {
        $pageTitle = 'Email Unverified Providers';
        $providers = $this->providerData('emailUnverified');
        return view('admin.providers.list', compact('pageTitle', 'providers'));
    }

    public function kycUnverifiedProviders() {
        $pageTitle = 'KYC Unverified Providers';
        $providers = $this->providerData('kycUnverified');
        return view('admin.providers.list', compact('pageTitle', 'providers'));
    }

    public function kycPendingProviders() {
        $pageTitle = 'KYC Pending Providers';
        $providers = $this->providerData('kycPending');
        return view('admin.providers.list', compact('pageTitle', 'providers'));
    }

    public function emailVerifiedProviders() {
        $pageTitle = 'Email Verified Providers';
        $providers = $this->providerData('emailVerified');
        return view('admin.providers.list', compact('pageTitle', 'providers'));
    }

    public function mobileUnverifiedProviders() {
        $pageTitle = 'Mobile Unverified Providers';
        $providers = $this->providerData('mobileUnverified');
        return view('admin.providers.list', compact('pageTitle', 'providers'));
    }

    public function mobileVerifiedProviders() {
        $pageTitle = 'Mobile Verified Providers';
        $providers = $this->providerData('mobileVerified');
        return view('admin.providers.list', compact('pageTitle', 'providers'));
    }

    public function ProvidersWithBalance() {
        $pageTitle = 'Providers with Balance';
        $providers = $this->providerData('withBalance');
        return view('admin.providers.list', compact('pageTitle', 'providers'));
    }

    protected function providerData($scope = null) {
        if ($scope) {
            $providers = Provider::$scope();
        } else {
            $providers = Provider::query();
        }
        return $providers->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function detail($id) {
        $provider  = Provider::findOrFail($id);
        $pageTitle = 'Provider Detail - ' . $provider->username;

        $totalWithdrawals = Withdrawal::where('provider_id', $provider->id)->approved()->sum('amount');
        $totalTransaction = Transaction::where('provider_id', $provider->id)->count();
        $countries        = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('admin.providers.detail', compact('pageTitle', 'provider', 'totalWithdrawals', 'totalTransaction', 'countries'));
    }

    public function kycDetails($id) {
        $pageTitle = 'KYC Details';
        $provider  = Provider::findOrFail($id);
        return view('admin.providers.kyc_detail', compact('pageTitle', 'provider'));
    }

    public function kycApprove($id) {
        $provider     = Provider::findOrFail($id);
        $provider->kv = Status::KYC_VERIFIED;
        $provider->save();

        notify($provider, 'KYC_APPROVE', []);

        $notify[] = ['success', 'KYC approved successfully'];
        return to_route('admin.providers.kyc.pending')->withNotify($notify);
    }

    public function kycReject(Request $request, $id) {
        $request->validate([
            'reason' => 'required',
        ]);
        $provider                       = Provider::findOrFail($id);
        $provider->kv                   = Status::KYC_UNVERIFIED;
        $provider->kyc_rejection_reason = $request->reason;
        $provider->save();

        notify($provider, 'KYC_REJECT', [
            'reason' => $request->reason,
        ]);

        $notify[] = ['success', 'KYC rejected successfully'];
        return to_route('admin.providers.kyc.pending')->withNotify($notify);
    }

    public function update(Request $request, $id) {
        $provider     = Provider::findOrFail($id);
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array) $countryData;
        $countries    = implode(',', array_keys($countryArray));

        $countryCode = $request->country;
        $country     = $countryData->$countryCode->country;
        $dialCode    = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname'  => 'required|string|max:40',
            'email'     => 'required|email|string|max:40|unique:Providers,email,' . $provider->id,
            'mobile'    => 'required|string|max:40',
            'country'   => 'required|in:' . $countries,
        ]);

        $exists = Provider::where('mobile', $request->mobile)->where('dial_code', $dialCode)->where('id', '!=', $provider->id)->exists();
        if ($exists) {
            $notify[] = ['error', 'The mobile number already exists.'];
            return back()->withNotify($notify);
        }

        $provider->mobile    = $request->mobile;
        $provider->firstname = $request->firstname;
        $provider->lastname  = $request->lastname;
        $provider->email     = $request->email;

        $provider->address      = $request->address;
        $provider->city         = $request->city;
        $provider->state        = $request->state;
        $provider->zip          = $request->zip;
        $provider->country_name = $country;
        $provider->dial_code    = $dialCode;
        $provider->country_code = $countryCode;

        $provider->ev = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $provider->sv = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $provider->ts = $request->ts ? Status::ENABLE : Status::DISABLE;
        if (!$request->kv) {
            $provider->kv = Status::KYC_UNVERIFIED;
            if ($provider->kyc_data) {
                foreach ($provider->kyc_data as $kycData) {
                    if ($kycData->type == 'file') {
                        fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
                    }
                }
            }
            $provider->kyc_data = null;
        } else {
            $provider->kv = Status::KYC_VERIFIED;
        }
        $provider->save();

        $notify[] = ['success', 'User details updated successfully'];
        return back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id) {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'act'    => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
        ]);

        $provider = Provider::findOrFail($id);
        $amount   = $request->amount;
        $trx      = getTrx();

        $transaction = new Transaction();

        if ($request->act == 'add') {
            $provider->balance += $amount;

            $transaction->trx_type = '+';
            $transaction->remark   = 'balance_add';

            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', 'Balance added successfully'];
        } else {
            if ($amount > $provider->balance) {
                $notify[] = ['error', $provider->username . ' doesn\'t have sufficient balance.'];
                return back()->withNotify($notify);
            }

            $provider->balance -= $amount;

            $transaction->trx_type = '-';
            $transaction->remark   = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[]       = ['success', 'Balance subtracted successfully'];
        }

        $provider->save();

        $transaction->provider_id  = $provider->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $provider->balance;
        $transaction->charge       = 0;
        $transaction->trx          = $trx;
        $transaction->details      = $request->remark;
        $transaction->save();

        notify($provider, $notifyTemplate, [
            'trx'          => $trx,
            'amount'       => showAmount($amount, currencyFormat: false),
            'remark'       => $request->remark,
            'post_balance' => showAmount($provider->balance, currencyFormat: false),
        ]);

        return back()->withNotify($notify);
    }

    public function login($id) {
        auth()->guard('provider')->loginUsingId($id);
        return to_route('provider.dashboard');
    }

    public function status(Request $request, $id) {
        $provider = Provider::findOrFail($id);
        if ($provider->status == Status::USER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255',
            ]);
            $provider->status     = Status::USER_BAN;
            $provider->ban_reason = $request->reason;
            $notify[]             = ['success', 'User banned successfully'];
        } else {
            $provider->status     = Status::USER_ACTIVE;
            $provider->ban_reason = null;
            $notify[]             = ['success', 'User unbanned successfully'];
        }
        $provider->save();
        return back()->withNotify($notify);
    }

    public function showNotificationSingleForm($id) {
        $provider = Provider::findOrFail($id);
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.providers.detail', $provider->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $provider->username;
        return view('admin.providers.notification_single', compact('pageTitle', 'provider'));
    }

    public function sendNotificationSingle(Request $request, $id) {
        $request->validate([
            'message' => 'required',
            'via'     => 'required|in:email,sms,push',
            'subject' => 'required_if:via,email,push',
            'image'   => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $imageUrl = null;
        if ($request->via == 'push' && $request->hasFile('image')) {
            $imageUrl = fileUploader($request->image, getFilePath('push'));
        }

        $template = NotificationTemplate::where('act', 'DEFAULT')->where($request->via . '_status', Status::ENABLE)->exists();
        if (!$template) {
            $notify[] = ['warning', 'Default notification template is not enabled'];
            return back()->withNotify($notify);
        }

        $provider = Provider::findOrFail($id);
        notify($provider, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ], [$request->via], pushImage: $imageUrl);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm() {
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $notifyToUser = Provider::notifyToUser();
        $providers    = Provider::active()->count();
        $pageTitle    = 'Notification to Verified Providers';

        if (session()->has('SEND_NOTIFICATION') && !request()->email_sent) {
            session()->forget('SEND_NOTIFICATION');
        }

        return view('admin.providers.notification_all', compact('pageTitle', 'providers', 'notifyToUser'));
    }

    public function sendNotificationAll(Request $request) {
        $request->validate([
            'via'                          => 'required|in:email,sms,push',
            'message'                      => 'required',
            'subject'                      => 'required_if:via,email,push',
            'start'                        => 'required|integer|gte:1',
            'batch'                        => 'required|integer|gte:1',
            'being_sent_to'                => 'required',
            'cooling_time'                 => 'required|integer|gte:1',
            'number_of_top_deposited_user' => 'required_if:being_sent_to,topDepositedProviders|integer|gte:0',
            'number_of_days'               => 'required_if:being_sent_to,notLoginProviders|integer|gte:0',
            'image'                        => ["nullable", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ], [
            'number_of_days.required_if'               => "Number of days field is required",
            'number_of_top_deposited_user.required_if' => "Number of top deposited user field is required",
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $template = NotificationTemplate::where('act', 'DEFAULT')->where($request->via . '_status', Status::ENABLE)->exists();
        if (!$template) {
            $notify[] = ['warning', 'Default notification template is not enabled'];
            return back()->withNotify($notify);
        }

        if ($request->being_sent_to == 'selectedProviders') {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['provider' => session()->get('SEND_NOTIFICATION')['provider']]);
            } else {
                if (!$request->user || !is_array($request->user) || empty($request->user)) {
                    $notify[] = ['error', "Ensure that the user field is populated when sending an email to the designated user group"];
                    return back()->withNotify($notify);
                }
            }
        }

        $scope         = $request->being_sent_to;
        $providerQuery = Provider::oldest()->active()->$scope();

        if (session()->has("SEND_NOTIFICATION")) {
            $totalUserCount = session('SEND_NOTIFICATION')['total_user'];
        } else {
            $totalUserCount = (clone $providerQuery)->count() - ($request->start - 1);
        }

        if ($totalUserCount <= 0) {
            $notify[] = ['error', "Notification recipients were not found among the selected user base."];
            return back()->withNotify($notify);
        }

        $imageUrl = null;

        if ($request->via == 'push' && $request->hasFile('image')) {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['image' => session()->get('SEND_NOTIFICATION')['image']]);
            }
            if ($request->hasFile("image")) {
                $imageUrl = fileUploader($request->image, getFilePath('push'));
            }
        }

        $providers = (clone $providerQuery)->skip($request->start - 1)->limit($request->batch)->get();

        foreach ($providers as $provider) {
            notify($provider, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ], [$request->via], pushImage: $imageUrl);
        }

        return $this->sessionForNotification($totalUserCount, $request);
    }

    private function sessionForNotification($totalUserCount, $request) {
        if (session()->has('SEND_NOTIFICATION')) {
            $sessionData = session("SEND_NOTIFICATION");
            $sessionData['total_sent'] += $sessionData['batch'];
        } else {
            $sessionData               = $request->except('_token');
            $sessionData['total_sent'] = $request->batch;
            $sessionData['total_user'] = $totalUserCount;
        }

        $sessionData['start'] = $sessionData['total_sent'] + 1;

        if ($sessionData['total_sent'] >= $totalUserCount) {
            session()->forget("SEND_NOTIFICATION");
            $message = ucfirst($request->via) . " notifications were sent successfully";
            $url     = route("admin.providers.notification.all");
        } else {
            session()->put('SEND_NOTIFICATION', $sessionData);
            $message = $sessionData['total_sent'] . " " . $sessionData['via'] . "  notifications were sent successfully";
            $url     = route("admin.providers.notification.all") . "?email_sent=yes";
        }
        $notify[] = ['success', $message];
        return redirect($url)->withNotify($notify);
    }

    public function countBySegment($methodName) {
        return Provider::active()->$methodName()->count();
    }

    public function list() {
        $query = Provider::active();

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . request()->search . '%')->orWhere('username', 'like', '%' . request()->search . '%');
            });
        }
        $providers = $query->orderBy('id', 'desc')->paginate(getPaginate());
        return response()->json([
            'success'   => true,
            'providers' => $providers,
            'more'      => $providers->hasMorePages(),
        ]);
    }

    public function notificationLog($id) {
        $provider  = Provider::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $provider->username;
        $logs      = NotificationLog::where('provider_id', $id)->with('provider')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs', 'provider'));
    }
}
