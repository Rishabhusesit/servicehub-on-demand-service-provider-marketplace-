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
                                    <th>@lang('Code')</th>
                                    <th>@lang('Discount Type')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Expire Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>
                                            <span>{{ $coupon->name }}</span>
                                        </td>

                                        <td>
                                            <span>{{ $coupon->code }}</span>
                                        </td>

                                        <td>
                                            @if ($coupon->discount_type == 1)
                                                <span class="text--small badge font-weight-normal badge--primary">
                                                    {{ $coupon->couponType }}</span>
                                            @else
                                                <span class="text--small badge font-weight-normal badge--dark">
                                                    {{ $coupon->couponType }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ getAmount($coupon->amount) }}
                                            {{ $coupon->discount_type == 1 ? gs()->cur_text : '%' }}
                                        </td>

                                        <td>
                                            @php
                                                echo $coupon->statusBadge;
                                            @endphp
                                        </td>

                                        <td>
                                            {{ showDateTime($coupon->end_date, 'd M, Y') }}
                                            @if (now()->toDateString() > $coupon->end_date)
                                                <span class="text--danger">(@lang('Expired'))</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.promotion.coupon.edit', $coupon->id) }}"
                                                   class="btn btn-sm btn-outline--primary"><i class="las la-pen"></i>
                                                    @lang('Edit')</a>

                                                @if ($coupon->status == Status::DISABLE)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline--success confirmationBtn"
                                                            data-action="{{ route('admin.promotion.coupon.status', $coupon->id) }}"
                                                            data-question="@lang('Are you sure to enable this coupon?')">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline--danger confirmationBtn"
                                                            data-action="{{ route('admin.promotion.coupon.status', $coupon->id) }}"
                                                            data-question="@lang('Are you sure to disable this coupon?')">
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
                @if ($coupons->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($coupons) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search by coupon" />
    <a href="{{ route('admin.promotion.coupon.create') }}" class="btn btn-sm btn-outline--primary"><i
           class="las la-plus"></i>@lang('Add New')</a>
@endpush
