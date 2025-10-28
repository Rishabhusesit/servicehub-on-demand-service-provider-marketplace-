@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="profile-wrapper">
        <div class="profile-header flex-between">
            <h5 class="profile-header__title">@lang('Personal Information')</h5>
        </div>
        <form action="{{ route('provider.profile.setting') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="profile-info py-60">
                <div class="profile-image-inner">
                    <div class="profile-image">
                        <img id="user"
                             src="{{ getImage(getFilePath('providerProfile') . '/' . $provider->image, getFileSize('providerProfile')) }}"
                             alt="@lang('image')">
                    </div>
                    <div class="profile-image-upload">
                        <input id="userChange" type="file" name="image">
                        <label for="userChange"><i class="las la-camera"></i></label>
                    </div>
                </div>
                <div class="profile-info-content">
                    <p class="profile-name">{{ __($provider->fullname) }}</p>
                    <p class="profile-contact-info"> <span>{{ $provider->mobile }}</span> <span>|</span>
                        <span>{{ __($provider->email) }}</span>
                    </p>
                    <p class="profile-designation"></p>
                    <div class="profile-overview">{{ $completeOrder }} @lang('successful services') |
                        <strong>{{ showAmount($totalEarned) }}</strong> @lang('earned')
                    </div>

                    <div class="flex-align gap-2">
                        <div class="profile-rating">
                            <span class="profile-rating__icon"><i class="las la-star"></i></span>
                            <p class="profile-rating__count"><span>{{ __(round($provider->average_rating, 1)) }}</span> /
                                <span>@lang('5.0')</span>
                            </p>
                        </div>
                        @if (gs('kv'))
                            @if ($provider->kv == Status::KYC_VERIFIED)
                                <div class="profile-badge">

                                    <span class="profile-badge__icon"><i class="lar la-check-circle"></i></span>
                                    <span class="profile-badge__text">@lang('Verified')</span>
                                </div>
                            @else
                                <div class="profile-badge bg-danger">
                                    <span class="profile-badge__icon"><i class="las la-ban"></i></span>
                                    <span class="profile-badge__text">@lang('Unverified')</span>
                                </div>
                            @endif
                    </div>
                    @endif
                </div>
            </div>
    </div>


    <div class="profile-content">
        <div class="profile-block">
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label class="form--label">@lang('First Name')</label>
                    <input type="text" class="form--control" name="firstname" value="{{ $provider->firstname }}"
                           required>
                </div>

                <div class="col-sm-6 form-group">
                    <label class="form--label">@lang('Last Name')</label>
                    <input type="text" class="form--control" name="lastname" value="{{ $provider->lastname }}" required>
                </div>

            </div>
        </div>
        <hr class="mt-0">
        <div class="profile-block">
            <div class="row">
                <div class="col-12 form-group">
                    <label class="form--label">@lang('Full Address')</label>
                    <input type="text" class="form--control" name="address" value="{{ $provider->address }}">
                </div>

                <div class="col-sm-4 form-group">
                    <label class="form--label">@lang('Country')</label>
                    <input type="text" class="form--control border" value="{{ $provider->country_name }}" readonly>
                </div>


                <div class="col-sm-4 form-group">
                    <label class="form--label">@lang('City')</label>
                    <input type="text" class="form--control" name="city" value="{{ $provider->city }}">
                </div>


                <div class="col-sm-4 form-group">
                    <label class="form--label">@lang('Zip Code')</label>
                    <input type="text" class="form--control" name="zip" value="{{ $provider->zip }}">
                </div>

                <div class="col-12 text-end form-group">
                    <button type="submit" class="btn btn--base">@lang('Update')</button>
                </div>
            </div>
        </div>
    </div>

    </form>

    </div>
@endsection
