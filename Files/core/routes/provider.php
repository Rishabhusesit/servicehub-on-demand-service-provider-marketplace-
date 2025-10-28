<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Provider\Auth')->name('provider.')->middleware('guest')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->middleware('provider')->withoutMiddleware('provider.guest')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register');
        Route::post('check-provider', 'checkProvider')->name('checkProvider')->withoutMiddleware('guest');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::middleware('provider')->name('provider.')->group(function () {

    Route::get('provider-data', 'Provider\ProviderController@providerData')->name('data');
    Route::post('provider-data-submit', 'Provider\ProviderController@providerDataSubmit')->name('data.submit');

    //authorization
    Route::middleware('registration.complete')->namespace('Provider')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });

    Route::middleware(['check.status', 'registration.complete'])->group(function () {
        Route::namespace('Provider')->group(function () {
            Route::controller('ProviderController')->group(function () {

                Route::get('dashboard', 'dashboard')->name('dashboard');
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');

                Route::get('work-details', 'workDetails')->name('work.details');
                Route::post('work-details', 'submitWorkDetails');

                Route::get('find-work', 'findWork')->name('find.work');
                Route::post('order-accept/{order_id}', 'orderAccept')->name('order.accept');
                Route::get('order-accepted/{order_id}', 'orderAccepted')->name('order.accepted');

                Route::get('service-history', 'serviceHistory')->name('service.history');
                Route::get('service-details/{order_id}', 'serviceDetails')->name('service.details');
                Route::post('order-complete-request/{order_id}', 'orderCompleteRequest')->name('order.complete.request');

                Route::get('transactions', 'transactions')->name('transactions');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');
            });

            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::get('/', 'withdrawMoney');
                Route::post('/', 'withdrawStore')->name('.money');
                Route::get('preview', 'withdrawPreview')->name('.preview');
                Route::post('preview', 'withdrawSubmit')->name('.submit');
                Route::get('history', 'withdrawLog')->name('.history');
            });
        });
    });
});

Route::middleware('provider')->name('provider.')->group(function () {
    Route::controller('ConversationController')->group(function () {
        Route::post('send-message/{id}', 'sendMessage')->name('message.send');
    });
});
