@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="profile-wrapper">
        <div class="profile-content">
            <div class="profile-block pb-4">
                <h5 class="card-title">@lang('KYC Documents')</h5>
            </div>
            <div class="profile-block">
                @if ($provider->kyc_data)
                    <ul class="list-group list-group-flush">
                        @foreach ($provider->kyc_data as $val)
                            @continue(!$val->value)
                            <li class="list-group-item  d-flex justify-content-between align-items-center px-0">
                                {{ __($val->name) }}
                                <span>
                                    @if ($val->type == 'checkbox')
                                        {{ implode(',', $val->value) }}
                                    @elseif($val->type == 'file')
                                        <a
                                           href="{{ route('provider.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}"><i
                                               class="fa-regular fa-file"></i> @lang('Attachment') </a>
                                    @else
                                        <p>{{ __($val->value) }}</p>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <h5 class="text-center">@lang('KYC data not found')</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
