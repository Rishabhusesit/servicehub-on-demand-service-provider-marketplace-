@extends($activeTemplate . 'layouts.master')
@section('content')
    <div>
        <div class="profile-header flex-between">
            <h5 class="profile-header__title">@lang('Personal Information')</h5>
        </div>

        <div class="py-60">
            <form action="{{ route('user.profile.setting') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row gy-4">
                    <div class="col-xl-5">
                        <div class="profile-info">
                            <div>
                                <div class="profile-image-inner">
                                    <div class="profile-image">
                                        <img id="user"
                                             src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}"
                                             alt="@lang('profile-image')">
                                    </div>
                                    <div class="profile-image-upload">
                                        <input id="userChange" type="file" name="image" accept=".jpg,.png,.jpeg">
                                        <label for="userChange"><i class="las la-camera"></i></label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="mt-3 text-muted"> @lang('Supported Files:')
                                        <b>@lang('.jpg,.jpeg,.png')</b>
                                        @lang('Image will be resized into') <b>{{ getFileSize('userProfile') }}</b>@lang('px')
                                    </small>
                                </div>
                            </div>
                        </div>

                        <ul class="profile-meta">
                            <li>
                                <strong>@lang('Username')</strong>
                                <span>{{ $user->username }}</span>
                            </li>
                            <li>
                                <strong>@lang('Email')</strong>
                                <span>{{ $user->email }}</span>
                            </li>
                            <li>
                                <strong>@lang('Mobile')</strong>
                                <span>{{ $user->mobile }}</span>
                            </li>
                            <li>
                                <strong>@lang('Country')</strong>
                                <span>{{ $user->country_name }}</span>
                            </li>

                        </ul>
                    </div>
                    <div class="col-xl-7">
                        <div class="profile-content">
                            <div class="profile-block">
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label class="form--label">@lang('First Name')</label>
                                        <input type="text" class="form--control" name="firstname"
                                               value="{{ $user->firstname }}" required>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label class="form--label">@lang('Last Name')</label>
                                        <input type="text" class="form--control" name="lastname"
                                               value="{{ $user->lastname }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-block">
                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label class="form--label">@lang('Full Address')</label>
                                        <input type="text" class="form--control" name="address" value="{{ $user->address }}">
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label class="form--label">@lang('City')</label>
                                        <input type="text" class="form--control" name="city" value="{{ $user->city }}">
                                    </div>


                                    <div class="col-sm-6 form-group">
                                        <label class="form--label">@lang('Zip Code')</label>
                                        <input type="text" class="form--control" name="zip" value="{{ $user->zip }}">
                                    </div>

                                    <div class="col-12 form-group d-flex justify-content-end">
                                        <button type="submit" class="btn btn--base">@lang('Update')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .profile-block__title i {
            height: 40px;
            width: 40px;
            background-color: hsl(var(--base));
            border-radius: 8px;
            font-size: 20px;
            place-content: center;
            color: hsl(var(--white));
            flex-shrink: 0;
            text-align: center
        }

        .profile-meta {
            max-width: 90%;
            margin-top: 16px;
        }

        .profile-meta li {}

        .profile-meta li strong {
            min-width: 100px;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            padding-right: 5px;
        }

        .profile-meta li strong::after {
            content: ':';
        }

        .profile-meta li:not(:last-child) {
            margin-bottom: 24px;
        }
    </style>
@endpush
