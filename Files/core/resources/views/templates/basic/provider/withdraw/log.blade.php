@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center mt-2">
        <div class="col-lg-12">
            <div class="table-top">
                <div class="table-top__wrapper justify-content-between flex-wrap gap-3">
                    <form method="GET">
                        <div class="table-search">
                            <button type="submit" class="table-search__icon"><i class="las la-search"></i></button>
                            <input type="search" class="form--control" name="search" placeholder="@lang('Search...')"
                                   value="{{ request()->search }}">
                        </div>
                    </form>
                    <a href="{{ route('provider.withdraw.money') }}" class="btn btn--base">@lang('New Withdraw')</a>
                </div>

            </div>

            <table class="table  table--collapse table--responsive--xl">
                <thead>
                    <tr>
                        <th>@lang('Gateway | Transaction')</th>
                        <th class="text-center">@lang('Initiated')</th>
                        <th class="text-center">@lang('Amount')</th>
                        <th class="text-center">@lang('Conversion')</th>
                        <th class="text-center">@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($withdraws as $withdraw)
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
                            <td class="text-lg-center text-end">
                                {{ showDateTime($withdraw->created_at) }} <br>
                                {{ diffForHumans($withdraw->created_at) }}
                            </td>
                            <td class="text-lg-center text-end">
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
                            <td class="text-lg-center text-end">
                                <div>
                                    {{ showAmount(1) }} =
                                    {{ showAmount($withdraw->rate, currencyFormat: false) }}
                                    {{ __($withdraw->currency) }}
                                    <br>
                                    <strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }}
                                        {{ __($withdraw->currency) }}</strong>
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
            @if ($withdraws->hasPages())
                <div class="pt-4">
                    {{ paginateLinks($withdraws) }}
                </div>
            @endif
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
                    <button type="button" class="btn btn-dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
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
