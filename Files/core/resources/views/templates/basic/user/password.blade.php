@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $passwordContent = getContent('change_password.content', true);
    @endphp


    <div class="profile-wrapper">
        <div class="profile-content">
            <div class="profile-block pb-4">
                <h5 class="profile-block__heading">{{ __($passwordContent?->data_values?->heading) }}</h5>
                <p class="profile-block__note">{{ __($passwordContent?->data_values?->description) }}</p>
            </div>
            <div class="profile-block">
                <form method="post">
                    <div class="row">
                        @csrf
                        <div class="form-group">
                            <label for="cuurent-password" class="form--label">@lang('Current Password')</label>
                            <div class="position-relative">
                                <input id="cuurent-password" type="password" class="form-control form--control"
                                    placeholder="@lang('Current Password')" name="current_password" required
                                    autocomplete="current-password">
                                <span class="password-show-hide fas fa-eye-slash toggle-password fa-eye"
                                    id="#cuurent-password"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-password" class="form--label">@lang('New Password')</label>
                            <div class="position-relative">
                                <input id="new-password" type="password"
                                    class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                    name="password" required autocomplete="current-password"
                                    placeholder="@lang('New Password')">
                                <span class="password-show-hide fas fa-eye-slash toggle-password fa-eye"
                                    id="#new-password"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password" class="form--label">@lang('Confirm Password')</label>
                            <div class="position-relative">
                                <input id="confirm-password" type="password" class="form-control form--control"
                                    placeholder="@lang('Confirm Password')" name="password_confirmation" required
                                    autocomplete="current-password">
                                <span class="password-show-hide fas fa-eye-slash toggle-password fa-eye"
                                    id="#confirm-password"></span>
                            </div>
                        </div>
                        <div class="col-12 form-group d-flex justify-content-end">
                            <button type="submit" class="btn btn--base">@lang('Update')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
