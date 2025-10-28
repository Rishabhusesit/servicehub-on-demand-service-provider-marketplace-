@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Provider')</th>
                                    <th>@lang('Email-Mobile')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Balance')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($providers as $provider)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $provider->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.providers.detail', $provider->id) }}"><span>@</span>{{ $provider->username }}</a>
                                            </span>
                                        </td>

                                        <td>
                                            {{ $provider->email }}<br>{{ $provider->mobileNumber }}
                                        </td>
                                        <td>
                                            <span class="fw-bold" title="{{ $provider->country_name }}">{{ $provider->country_code }}</span>
                                        </td>

                                        <td>
                                            {{ showDateTime($provider->created_at) }} <br>
                                            {{ diffForHumans($provider->created_at) }}
                                        </td>


                                        <td>
                                            <span class="fw-bold">

                                                {{ showAmount($provider->balance) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.providers.detail', $provider->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                                @if (request()->routeIs('admin.providers.kyc.pending'))
                                                    <a href="{{ route('admin.providers.kyc.details', $provider->id) }}" target="_blank" class="btn btn-sm btn-outline--dark">
                                                        <i class="las la-user-check"></i>@lang('KYC Data')
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <x-empty-message />
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($providers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($providers) }}
                    </div>
                @endif
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush
