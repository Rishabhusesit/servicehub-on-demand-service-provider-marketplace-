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
                                    <th>@lang('Area')</th>
                                    <th>@lang('Delivery Charge')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cities as $city)
                                    <tr>
                                        <td>
                                            <span>{{ __($city->name) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $city->areas_count }}</span>
                                        </td>
                                        <td>{{ showAmount($city->delivery_charge) }}</td>
                                        <td>
                                            @php
                                                echo $city->statusBadge;
                                            @endphp
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-outline--primary customModalBtn btn-sm"
                                                        data-modal_title="@lang('Update City')" data-resource="{{ $city }}">
                                                    <i class="las la-pen"></i>@lang('Edit')
                                                </button>

                                                @if ($city->status == Status::DISABLE)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline--success confirmationBtn"
                                                            data-action="{{ route('admin.zone.city.status', $city->id) }}"
                                                            data-question="@lang('Are you sure to enable this city?')">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline--danger confirmationBtn"
                                                            data-action="{{ route('admin.zone.city.status', $city->id) }}"
                                                            data-question="@lang('Are you sure to disable this city?')">
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
                @if ($cities->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($cities) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="customModal" role="dialog" tabindex="-1">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.zone.city.save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group ">
                                <label>@lang('Name')</label>
                                <input class="form-control" type="text" name="name" required
                                       value="{{ old('name') }}">
                            </div>

                            <div class="form-group ">
                                <label>@lang('Delivery Charge')</label>
                                <div class="input-group">
                                    <input class="form-control" type="number" step="any" name="delivery_charge" required value="{{ old('delivery_charge') }}">
                                    <span class="input-group-text">{{ gs('cur_text') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="Search by city..." />
    <button type="button" class="btn btn-sm btn-outline--primary customModalBtn" data-modal_title="@lang('Add New City')"><i
           class="las la-plus"></i>@lang('Add New')</button>
@endpush
