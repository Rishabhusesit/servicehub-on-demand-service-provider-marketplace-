@php
    $contactContent = getContent('contact_us.content', true);
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="contact-section mt-120">
        <div class="container custom-container">
            <div class="row gy-4">
                <div class="col-sm-6 col-lg-4">
                    <div class="contact-info-item">
                        <div class="contact-info-item__icon"><i class="las la-envelope"></i></div>
                        <h5 class="contact-info-item__title">@lang('Email')</h5>
                        <a class="contact-info-item__text" href="mailto:{{ $contactContent?->data_values?->email_address }}">{{ $contactContent?->data_values?->email_address }}</a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="contact-info-item">
                        <div class="contact-info-item__icon"><i class="las la-phone"></i></div>
                        <h5 class="contact-info-item__title">@lang('Phone')</h5>
                        <a class="contact-info-item__text" href="tel:{{ $contactContent?->data_values?->contact_number }}">{{ $contactContent?->data_values?->contact_number }}</a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="contact-info-item">
                        <div class="contact-info-item__icon"><i class="las la-map-marked"></i></div>
                        <h5 class="contact-info-item__title">@lang('Office')</h5>
                        <a class="contact-info-item__text" href="javascript:void(0)">{{ $contactContent?->data_values?->contact_details }}</a>
                    </div>
                </div>
            </div>

            <div class="row mt-60 gy-5">
                <div class="col-lg-6">
                    <div class="contact-map">
                        <iframe src="{{ $contactContent?->data_values?->map_link }}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="account-form">
                        <div class="contact-form-heading">
                            <h2 class="contact-form-heading__title">{{ __($contactContent?->data_values?->heading) }}</h2>
                            <p class="contact-form-heading__desc">{{ __($contactContent?->data_values?->subheading) }}</p>
                        </div>
                        <form action="{{ route('contact') }}" method="POST" class="verify-gcaptcha">
                            @csrf
                            <div class="form-group">
                                <label class="form--label">@lang('Name')</label>
                                <input id="name" name="name" type="text" class="form--control border"
                                       placeholder="@lang('Enter your name')" value="{{ old('name', $user?->fullname) }}"
                                       @if ($user && $user->profile_complete) readonly @endif required>
                            </div>
                            <div class="form-group">
                                <label class="form--label">@lang('Email')</label>
                                <input id="email" type="email" name="email" class="form--control border"
                                       placeholder="@lang('Enter your email')" value="{{ old('email', $user?->email) }}"
                                       @if ($user) readonly @endif required>
                            </div>
                            <div class="form-group">
                                <label class="form--label">@lang('Subject')</label>
                                <input id="Subject" name="subject" type="text" class="form--control"
                                       placeholder="@lang('Enter subject')" value="{{ old('subject') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form--label">@lang('Message')</label>
                                <textarea id="Message" name="message" class="form--control" placeholder="@lang('Type message here')" required>{{ old('message') }}</textarea>
                            </div>
                            <x-captcha :isFrontend="true" />
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (isset($sections->secs) && $sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection
