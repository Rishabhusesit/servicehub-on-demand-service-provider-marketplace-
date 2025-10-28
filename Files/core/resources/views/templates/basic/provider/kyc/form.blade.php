@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="profile-wrapper">
        <div class="profile-content">
            <div class="profile-block pb-4">
                <h5 class="card-title">@lang('KYC Form')</h5>
            </div>
            <div class="profile-block">
                <form action="{{ route('provider.kyc.submit') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <x-viser-form identifier="act" identifierValue="kyc" />

                    <div class="form-group">
                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
