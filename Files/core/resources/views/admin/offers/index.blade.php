@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Discount Type')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Expire Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($offers as $offer)
                                    <tr>
                                        <td>
                                            {{ $offer->name }}
                                        </td>

                                        <td>
                                            @if ($offer->discount_type == 1)
                                                <span class="text--small badge font-weight-normal badge--primary">
                                                    {{ $offer->offerType }}</span>
                                            @else
                                                <span class="text--small badge font-weight-normal badge--dark">
                                                    {{ $offer->offerType }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ getAmount($offer->amount) }}
                                            {{ $offer->discount_type == 1 ? gs()->cur_text : '%' }}
                                        </td>

                                        <td>
                                            @php
                                                echo $offer->statusBadge;
                                            @endphp
                                        </td>

                                        <td>
                                            {{ showDateTime($offer->end_date, 'd M, Y') }}

                                            @if (now()->toDateString() > $offer->end_date)
                                                <span class="text--danger">(@lang('Expired'))</span>
                                            @endif

                                        </td>

                                        <td>
                                            <a href="{{ route('admin.promotion.offer.edit', $offer->id) }}"
                                                class="btn btn-sm btn-outline--primary ms-1"><i class="las la-pen"></i>
                                                @lang('Edit')</a>

                                            @if ($offer->status == Status::DISABLE)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="{{ route('admin.promotion.offer.status', $offer->id) }}"
                                                    data-question="@lang('Are you sure to enable this offer?')">
                                                    <i class="la la-eye"></i> @lang('Enable')
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.promotion.offer.status', $offer->id) }}"
                                                    data-question="@lang('Are you sure to disable this offer?')">
                                                    <i class="la la-eye-slash"></i> @lang('Disable')
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                   <x-empty-message />
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($offers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($offers) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search by offer" />
    <a href="{{ route('admin.promotion.offer.create') }}" class="btn btn-sm btn-outline--primary"><i
            class="las la-plus"></i>@lang('Add New')</a>
@endpush
