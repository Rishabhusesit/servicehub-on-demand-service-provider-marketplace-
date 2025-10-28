@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="profile-wrapper--lg">
        <div class="walet-wrapper pb-60">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="notice"></div>
                    @php
                        $kyc = getContent('kyc.content', true);
                    @endphp
                    @if (auth('provider')->user()->kv == Status::KYC_UNVERIFIED && auth('provider')->user()->kyc_rejection_reason)
                        <div class="alert alert--danger mb-5" role="alert">
                            <div class="alert__icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert__content">
                                <h4 class="alert__title">@lang('KYC Documents Rejected')</h4>
                                <button class="alert__link" data-bs-toggle="modal"
                                        data-bs-target="#kycRejectionReason">@lang('Show Reason')
                                </button>
                                <p class="alert__desc">{{ __($kyc?->data_values?->reject) }}
                                    <a class="alert__link" href="{{ route('provider.kyc.form') }}">@lang('Click Here to Re-submit Documents')</a>.
                                </p>
                                <a href="{{ route('provider.kyc.data') }}" class="alert__link">@lang('See KYC Data')</a>
                            </div>
                        </div>
                    @elseif(auth('provider')->user()->kv == Status::KYC_UNVERIFIED)
                        <div class="alert alert--danger mb-5" role="alert">
                            <div class="alert__icon">
                                <i class="far fa-address-card"></i>
                            </div>
                            <div class="alert__content">
                                <h5 class="alert__title">@lang('KYC Verification required')</h5>
                                <p class="alert__desc ">{{ __($kyc?->data_values?->required) }}
                                    <a href="{{ route('provider.kyc.form') }}" class="alert__link">@lang('Click Here to Submit Documents')
                                    </a>
                                </p>
                            </div>
                        </div>
                    @elseif(auth('provider')->user()->kv == Status::KYC_PENDING)
                        <div class="alert alert--warning mb-5" role="alert">
                            <div class="alert__icon">
                                <i class="fas fa-spinner"></i>
                            </div>
                            <div class="alert__content">
                                <h5 class="alert__title">@lang('KYC Verification pending')</h5>
                                <p class="alert__desc">{{ __($kyc?->data_values?->pending) }}
                                    <a href="{{ route('provider.kyc.data') }}" class="alert__link">@lang('See KYC Data')
                                    </a>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if (auth('provider')->user()->kv == Status::KYC_UNVERIFIED && auth('provider')->user()->kyc_rejection_reason)
                <div class="modal fade mb-5" id="kycRejectionReason">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ auth('provider')->user()->kyc_rejection_reason }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row gy-4">
                <div class="col-xsm-6 col-sm-6 col-xl-4">
                    <a href="{{ route('provider.service.history') }}" class="walet-card">
                        <div class="walet-card__content">
                            <p class="walet-card__title">@lang('Total Earned')</p>
                            <h4 class="walet-card__amount">
                                {{ gs('cur_sym') }}{{ showAmount($widget['total_earned'], currencyFormat: false) }}</h4>
                        </div>
                        <div class="walet-card__icon badge--warning">
                            <img src="{{ asset($activeTemplateTrue . 'images/earn.png') }}" alt="image">
                        </div>
                    </a>
                </div>
                <div class="col-xsm-6 col-sm-6 col-xl-4">
                    <a href="{{ route('provider.withdraw.history') }}" class="walet-card">
                        <div class="walet-card__content">
                            <p class="walet-card__title">@lang('Total Withdrawn')</p>
                            <h4 class="walet-card__amount">
                                {{ gs('cur_sym') }}{{ showAmount($widget['total_withdrawn'], currencyFormat: false) }}</h4>
                        </div>
                        <div class="walet-card__icon badge--success">
                            <img src="{{ asset($activeTemplateTrue . 'images/withdraw.png') }}" alt="image">
                        </div>
                    </a>
                </div>
                <div class="col-12 col-xl-4">
                    <a href="{{ route('provider.service.history') }}" class="walet-card">
                        <div class="walet-card__content">
                            <p class="walet-card__title">@lang('Due Amount')</p>
                            <h4 class="walet-card__amount">
                                {{ gs('cur_sym') }}{{ showAmount($widget['total_due'], currencyFormat: false) }}</h4>
                        </div>
                        <div class="walet-card__icon badge--danger">
                            <img src="{{ asset($activeTemplateTrue . 'images/due.png') }}" alt="image">
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row pb-60">
            <div class="col-12 col-md-6">
                <a href="javascript:void(0)" class="walet-balance">
                    <div class="walet-balance__content">
                        <p class="walet-balance__title">@lang('Available Balance')</p>
                        <h4>{{ showAmount($provider->balance) }} </h4>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6">
                <a href="{{ route('provider.service.history') }}" class="walet-balance">
                    <div class="walet-balance__content">
                        <p class="walet-balance__title">@lang('Incomplete Orders')</p>
                        <h4>{{ getAmount($widget['incomplete_order']) }} </h4>
                    </div>
                </a>
            </div>
        </div>
        <div class="transaction-history pb-60">
            <div class="profile-header mb-4 gap-3 flex-between">
                <h5 class="profile-header__title">@lang('Service History')</h5>

            </div>
            <table class="table table--border table--responsive--xl table--collapse">
                <thead>
                    <tr>
                        <th>@lang('Order ID')</th>
                        <th>@lang('Schedule')</th>
                        <th>@lang('Price')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Details')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->schedule_time }}<br>{{ showDateTime($order->schedule_date) }}</td>
                            <td>{{ showAmount($order->total) }}</td>
                            <td>
                                @php
                                    echo $order->orderStatusBadge;
                                @endphp
                            </td>
                            <td>
                                <a href="{{ route('provider.service.details', $order->id) }}"
                                   class="details-btn">@lang('Details') <span class="icon"><i
                                           class="las la-angle-right"></i></span></a>
                            </td>
                        </tr>

                    @empty
                        <x-empty-message />
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="withdraw-history pb-60">
            <div class="profile-header mb-4">
                <h5 class="profile-header__title">@lang('Withdraw History')</h5>
            </div>
            <div class="nav custom--tab nav-tabs" id="nav-tab" role="tablist">
                <div class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#Ongoing"
                            type="button">@lang('Ongoing')</button>
                </div>
                <div class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#History"
                            type="button">@lang('History')</button>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane show active" id="Ongoing">
                    <table class="table table--border table--responsive--xl table--collapse">
                        <thead>
                            <tr>
                                <th>@lang('Gateway | Transaction')</th>
                                <th class="text-center">@lang('Initiated')</th>
                                <th class="text-center">@lang('Amount')</th>
                                <th class="text-center">@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ongoingWithdraws ?? [] as $withdraw)
                                @php
                                    $details = [];
                                    foreach ($withdraw->withdraw_information ?? [] as $key => $info) {
                                        $details[] = $info;
                                        if ($info->type == 'file') {
                                            $details[$key]->value = route('provider.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-bold"><span class="text-primary">
                                                    {{ __($withdraw?->method?->name) }}</span></span>
                                            <br>
                                            <small>{{ $withdraw->trx }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{ showDateTime($withdraw->created_at) }} <br>
                                        {{ diffForHumans($withdraw->created_at) }}
                                    </td>
                                    <td class="text-center">
                                        <div>
                                            {{ showAmount($withdraw->amount) }} - <span class="text--danger"
                                                  data-bs-toggle="tooltip"
                                                  title="@lang('Processing Charge')">{{ showAmount($withdraw->charge) }} </span>
                                            <br>
                                            <strong data-bs-toggle="tooltip" title="@lang('Amount after charge')">
                                                {{ showAmount($withdraw->amount - $withdraw->charge) }}
                                            </strong>
                                        </div>

                                    </td>

                                    <td class="text-center">
                                        @php echo $withdraw->statusBadge @endphp
                                    </td>
                                    <td>
                                        <button class="btn btn--sm btn--base detailBtn"
                                                data-user_data="{{ json_encode($details) }}"
                                                @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                            <i class="la la-desktop"></i>
                                        </button>
                                    </td>
                                </tr>

                            @empty
                                <x-empty-message />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="History">
                    <table class="table table--border table--responsive--xl table--collapse">
                        <thead>
                            <tr>
                                <th>@lang('Gateway | Transaction')</th>
                                <th class="text-center">@lang('Initiated')</th>
                                <th class="text-center">@lang('Amount')</th>
                                <th class="text-center">@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawsLog as $withdraw)
                                @php
                                    $details = [];
                                    foreach ($withdraw->withdraw_information as $key => $info) {
                                        $details[] = $info;
                                        if ($info->type == 'file') {
                                            $details[$key]->value = route('provider.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-bold"><span class="text-primary">
                                                    {{ __($withdraw?->method?->name) }}</span></span>
                                            <br>
                                            <small>{{ $withdraw->trx }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{ showDateTime($withdraw->created_at) }} <br>
                                        {{ diffForHumans($withdraw->created_at) }}
                                    </td>
                                    <td class="text-center">
                                        <div>
                                            {{ showAmount($withdraw->amount) }} - <span class="text--danger"
                                                  data-bs-toggle="tooltip"
                                                  title="@lang('Processing Charge')">{{ showAmount($withdraw->charge) }} </span>
                                            <br>
                                            <strong data-bs-toggle="tooltip" title="@lang('Amount after charge')">
                                                {{ showAmount($withdraw->amount - $withdraw->charge) }}
                                            </strong>

                                        </div>
                                    </td>

                                    <td class="text-center">
                                        @php echo $withdraw->statusBadge @endphp
                                    </td>
                                    <td>
                                        <button class="btn btn--sm btn--base detailBtn"
                                                data-user_data="{{ json_encode($details) }}"
                                                @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                            <i class="la la-desktop"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <x-empty-message />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData list-group-flush">

                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn--sm"
                            data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    } else {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span"><a href="${element.value}"><i class="fa-regular fa-file"></i> @lang('Attachment')</a></span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .walet-card {
            color: #414141 !important;
        }
    </style>
@endpush
