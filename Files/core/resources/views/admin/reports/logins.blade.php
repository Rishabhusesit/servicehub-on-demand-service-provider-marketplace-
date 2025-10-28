@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Info')</th>
                                    <th>@lang('Login at')</th>
                                    <th>@lang('IP')</th>
                                    <th>@lang('Location')</th>
                                    <th>@lang('Browser | OS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginLogs as $log)
                                    <tr>
                                        <td>
                                            @if ($log->user_id != 0)
                                                <span class="fw-bold">@lang('User')</span>
                                            @else
                                                <span class="fw-bold">@lang('Provider')</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($log->user_id != 0)
                                                <span class="fw-bold">{{ $log?->user?->fullname }}</span>
                                                <br>
                                                <span class="small"> <a
                                                        href="{{ route('admin.users.detail', $log->user_id) }}"><span>@</span>{{ $log?->user?->username }}</a>
                                                </span>
                                            @else
                                                <span class="fw-bold">{{ $log?->provider?->fullname }}</span>
                                                <br>
                                                <span class="small"> <a
                                                        href="{{ route('admin.providers.detail', $log->provider_id) }}"><span>@</span>{{ $log?->provider?->username }}</a>
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ showDateTime($log->created_at) }} <br> {{ diffForHumans($log->created_at) }}
                                        </td>

                                        <td>
                                            <span class="fw-bold">
                                                <a
                                                    href="{{ route('admin.report.login.ipHistory', [$log->user_ip]) }}">{{ $log->user_ip }}</a>
                                            </span>
                                        </td>

                                        <td>{{ __($log->city) }} <br> {{ __($log->country) }}</td>
                                        <td>
                                            {{ __($log->browser) }} <br> {{ __($log->os) }}
                                        </td>
                                    </tr>
                                @empty
                                   <x-empty-message />
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($loginLogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($loginLogs) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    @if (request()->routeIs('admin.report.login.history'))
        <x-search-form placeholder="Search Username" dateSearch='yes' />
    @endif
@endpush
@if (request()->routeIs('admin.report.login.ipHistory'))
    @push('breadcrumb-plugins')
        <a href="https://www.ip2location.com/{{ $ip }}" target="_blank"
            class="btn btn-outline--primary">@lang('Lookup IP') {{ $ip }}</a>
    @endpush
@endif
