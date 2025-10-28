@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Parent')</th>
                                    <th>@lang('Service')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>


                            <tbody>
                                @forelse ($serviceOptions as $service)
                                    <tr>

                                        <td>

                                            <div class="user">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('serviceOption') . '/' . $service->image, getFileSize('serviceOption')) }}"
                                                         class="plugin_bg">
                                                </div>
                                                <span class="name">{{ __(Str::limit($service->name, 20)) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                if ($service->child()->count() > 0) {
                                                    echo __('Start From') . ' ' . showAmount($service->price) . '<br>';
                                                } else {
                                                    echo showAmount($service->price);
                                                }
                                            @endphp
                                        </td>

                                        <td>{{ __(Str::limit($service?->parent?->name, 20) ?? 'N/A') }}</td>

                                        <td>{{ __(Str::limit($service?->service?->name, 20)) }}</td>

                                        <td>
                                            @php
                                                echo $service->statusBadge;
                                            @endphp
                                        </td>
                                        
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.service.serviceOption.edit', $service->id) }}"
                                                   class="btn btn-sm btn-outline--primary"><i class="las la-pen"></i>
                                                    @lang('Edit')</a>

                                                @if ($service->status == Status::DISABLE)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline--success confirmationBtn"
                                                            data-action="{{ route('admin.service.serviceOption.status', $service->id) }}"
                                                            data-question="@lang('Are you sure to enable this service option?')">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline--danger confirmationBtn"
                                                            data-action="{{ route('admin.service.serviceOption.status', $service->id) }}"
                                                            data-question="@lang('Are you sure to disable this service option?')">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-empty-message />
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($serviceOptions->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($serviceOptions) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="Search by service..." />
    <a href="{{ route('admin.service.serviceOption.create') }}" class="btn btn-sm btn-outline--primary"><i
           class="las la-plus"></i>@lang('Add New')</a>
@endpush
