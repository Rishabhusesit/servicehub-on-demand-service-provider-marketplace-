@extends($activeTemplate . 'layouts.master')
@section('content')


    <div class="order-details__tab">
        <div class="nav custom--tab nav-tabs" id="nav-tab" role="tablist">
            <div class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#Details" type="button">@lang('Details')</button>
            </div>
            <div class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#chatinbox" type="button">@lang('Client')</button>
            </div>
        </div>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane active show" id="Details">
                <div class="row gy-5">
                    <div class="col-xl-7">
                        <div class="product-card product-card--row">
                            <div class="product-card__image">
                                <img src="{{ getImage(getFilePath('service') . '/' . $serviceName?->image, getFileSize('service')) }}" alt="@lang('image')">
                            </div>
                            <div class="product-card__content">
                                <h5 class="product-card__heading">{{ $serviceName?->name }}
                                </h5>
                                <div class="flex-align gap-2">
                                    <ul class="rating-list">
                                        @php
                                            $rating = $order?->review?->rating ?? 0;
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li class="rating-list__item">
                                                <span class="rating-list__text">
                                                    <i class="{{ $i <= $rating ? 'fa fa-star' : 'fa-regular fa-star' }}"></i>
                                                </span>
                                            </li>
                                        @endfor
                                    </ul>
                                    <p class="rating-count">
                                        <span>{{ __($rating) }}</span> / <span>@lang('5')</span>
                                    </p>
                                </div>
                                <p class="product-card__text">{{ $order->order_id }} @php echo $order->orderStatusBadge; @endphp</p>
                                <p class="product-card__price">{{ showAmount($order->total) }}</p>
                            </div>
                        </div>


                        @if ($order->status == Status::ORDER_PROCESSING)
                            <div class="summery-block mt-4 text-center">
                                <button type="button" class="w-100 btn--md btn--base btn--sm text-white text-center confirmationBtn" data-action="{{ route('provider.order.complete.request', $order->id) }}" data-question="@lang('Are you sure you want to mark this order as complete?')">
                                    @lang('Mark as Completed')</button>
                            </div>
                        @endif

                        @if ($order->status == Status::ORDER_REFUND)
                            <div class="summery-block product-card mt-4 text-center">
                                <strong class="text--danger">@lang('Client has sent a refund request for this service.')</strong>
                            </div>
                        @endif

                        <div class="summery-block product-card mt-4">
                            <h5 class="product-card__heading">@lang('Client Order Services')</h5><br>
                            <ul class="summery-list">
                                @foreach ($orderDetails as $orderDetail)
                                    @php
                                        $serviceOption = $orderDetail?->serviceOption;
                                        $service = $serviceOption?->service;
                                        $parentService = $serviceOption?->parent;
                                    @endphp

                                    <li class="summery-list-item">
                                        <div>
                                            <strong class="summery-list-item__title mb-0"> {{ $service?->name ?? '' }}</strong>
                                            <div class="summery-list-item__content">

                                                @if ($parentService?->name)
                                                    <span>{{ $parentService?->name ?? '' }}</span>
                                                @endif
                                                <span>{{ $serviceOption?->name ?? '' }} x {{ $orderDetail->qty }}</span>
                                            </div>
                                        </div>
                                        <span class="summery-list-item__price">{{ gs('cur_sym') }}{{ showAmount($orderDetail->price * $orderDetail->qty, currencyFormat: false) }}</span>
                                    </li>
                                @endforeach
                            </ul>



                            <ul class="list">
                                <li class="item">
                                    <p>@lang('Subtotal')</p>
                                    <span>{{ gs('cur_sym') }}{{ showAmount($order->sub_total, currencyFormat: false) }}</span>
                                </li>
                                <li class="item">
                                    <p>@lang('Delivery Charge')</p>
                                    <span>{{ gs('cur_sym') }}{{ showAmount($order->delivery_charge, currencyFormat: false) }}</span>
                                </li>
                                <li class="item">
                                    <p>@lang('Discount')</p>
                                    <span>{{ gs('cur_sym') }}{{ showAmount($order->discount, currencyFormat: false) }}</span>
                                </li>
                            </ul>
                            <div class="summery-block__total">
                                <strong>@lang('Amount to be paid')</strong>
                                <strong>{{ gs('cur_sym') }}{{ showAmount($order->total, currencyFormat: false) }}</strong>
                            </div>
                        </div>


                        <div class="comment mt-4">
                            <h5 class="comment__title">@lang('Client Review & Comment')</h5>
                            @if (optional($order->review)->rating)
                                <div class="flex-align gap-2">
                                    <ul class="rating-list">
                                        @php
                                            $rating = $order?->review?->rating;
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li class="rating-list__item">
                                                <span class="rating-list__text">
                                                    <i class="{{ $i <= $rating ? 'fa fa-star' : 'fa-regular fa-star' }}"></i>
                                                </span>
                                            </li>
                                        @endfor
                                    </ul>
                                    <p class="rating-count">
                                        <span>{{ __($rating) }}</span> / <span>@lang('5')</span>
                                    </p>
                                </div>
                                <div class="comment-wrapper">
                                    <p class="comment-wrapper__text">
                                        “{{ optional($order->review)->review }}“</p>
                                </div>
                            @else
                                <x-empty-message />
                            @endif
                        </div>
                    </div>


                    <div class="col-xl-5">
                        <div class="summery-box summery-box--border">
                            <h5 class="summery-box__title">@lang('Client Information')</h5>
                            <div class="summery-block">
                                <div class="payment-user-info style-two">
                                    <div class="text">
                                        <span class="title">@lang('Name:')</span>
                                        {{ $order?->user?->fullname }}
                                    </div>
                                    <div class="text">
                                        <span class="title">@lang('Mobile Number:')</span>
                                        {{ $order?->user?->dial_code }}{{ $order?->user?->mobile }}
                                    </div>

                                    <div class="text">
                                        <span class="title">@lang('Service Address:')</span>
                                        {{ __($order?->user?->address) }}
                                        {{ __($order?->city?->name) }},{{ __($order?->area?->name) }}
                                    </div>

                                    <div class="text">
                                        <span class="title">@lang('Contact Details:')</span>
                                        {{ __($order->contact_person_name) }},
                                        {{ __($order->contact_person_number) }}.
                                        {{ __($order->address) }}
                                    </div>
                                </div>
                            </div>

                            <h5 class="summery-box__title">@lang('Schedule')</h5>
                            <div class="summery-block">
                                <div class="shedule-wrapper">
                                    <div class="shedule-item">
                                        <h5 class="order-card__title">@lang('Time')</h5>
                                        <p class="text">{{ Str::upper($order->schedule_time) }}</p>
                                    </div>
                                    <div class="shedule-divide"></div>
                                    <div class="shedule-item">
                                        <h5 class="order-card__title">@lang('Date')</h5>
                                        <p class="text">{{ showDateTime($order->schedule_date) }}</p>
                                    </div>
                                </div>
                            </div>
                            <h5 class="summery-box__title">@lang('Payment History')</h5>

                            @if ($order->payment_type == Status::COD_PAYMENT)
                                <div class="custom--radio">
                                    <input type="radio" value="1" id="cash" class="form-check-input" name="payment_type" checked disabled>
                                    <i class="las la-wallet"></i>
                                    <label for="cash"> @lang('Cash On Delivery')</label>
                                </div>
                            @else
                                @if ($order->payment_status == Status::PAID)
                                    <div class="summery-block">
                                        <div class="payment-item">
                                            <div class="payment-item__right">
                                                <div class="payment-image">
                                                    <img src="{{ getImage(getFilePath('gateway') . '/' . $gateWayInfo?->image) }}" alt="">
                                                </div>
                                                <p>{{ $gateWayInfo?->name }}</p>
                                            </div>
                                        </div>
                                        <div class="payment-user-info">
                                            <div class="text"> <span class="bold">@lang('Transaction ID'):</span>
                                                {{ $orderPayment?->trx }}</div>

                                        </div>
                                    </div>

                                    <div class="summery-block">
                                        <div class="payment-success flex-align">
                                            <div class="payment-success__image">
                                                <img src="{{ asset($activeTemplateTrue . '/images/success.png') }}" alt="success">
                                            </div>
                                            <h5 class="payment-success__heading">@lang('Payment completed successfully')</h5>
                                        </div>
                                    </div>
                                @else
                                    <div class="summery-block">
                                        <div class="payment-success flex-align">
                                            <h5 class="payment-success__heading text-danger">@lang('Order payment pending')</h5>
                                        </div>
                                    </div>
                                @endif
                            @endif


                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="chatinbox">
                <div class="row gy-5">
                    <div class="col-xl-12">

                        <div class="chat custom--card">
                            <div class="chat__heading">
                                <div class="chat__heading-icon"><i class="lar la-comment"></i></div>
                                <h5 class="chat__heading-title">{{ __($order?->user?->fullname) }}</h5>
                            </div>
                            <div class="chat__body" id="chatBody">

                                @foreach ($conversation as $message)
                                    @if ($message->is_user != 1)
                                        <div class="message-item form--message">
                                            <div class="message-item__wrapper">

                                                <p class="message-item__text">{{ __($message->message) }}
                                                    <br>
                                                    @if ($message->attachment != null)
                                                        <a href="{{ getImage(getFilePath('conversation') . '/' . $message->attachment) }}" download="{{ $message->attachment }}">@lang('Download File')</a>
                                                    @endif

                                                </p>
                                                <div class="message-item__profile">
                                                    <img src="{{ getImage(getFilePath('providerProfile') . '/' . $order?->provider?->image, getFileSize('providerProfile'), true) }}" alt="profile-image">
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="message-item">
                                            <div class="message-item__wrapper">
                                                <div class="message-item__profile">
                                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $order?->user?->image, getFileSize('userProfile'), true) }}" alt="profile-image">
                                                </div>
                                                <p class="message-item__text">{{ __($message->message) }}
                                                    <br>
                                                    @if ($message->attachment != null)
                                                        <a href="{{ getImage(getFilePath('conversation') . '/' . $message->attachment) }}" download="{{ $message->attachment }}">@lang('Download File')</a>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="chat__footer">
                                <form action="{{ route('provider.message.send', $order->id) }}" class="w-100 messageForm chat__footer-wrapper" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="chat-file">
                                        <input type="file" class="chat-file__input" name="attachment">
                                        <label class="chat-file__icon"><i class="las la-paperclip"></i></label>
                                    </div>

                                    <div class="chat__box">
                                        <input data-emojiable="true" type="text" class="form--control message" name="message" placeholder="@lang('Type your message')" required>
                                        <button type="submit" class="chat__box-icon"><i class="las la-paper-plane"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <x-confirmation-modal closeBtn="btn--dark btn--sm" submitBtn="btn--base btn--sm" />
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/emoji.css') }}">
@endpush



@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/pusher.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/emoji.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/emoji-picker.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {



            window.addEventListener('load', function() {
                const activeTab = localStorage.getItem('activeTab');
                if (activeTab) {
                    const targetTabButton = document.querySelector(`[data-bs-target='${activeTab}']`);
                    if (targetTabButton) {
                        new bootstrap.Tab(targetTabButton).show();
                    }
                }

                scrollToBottom();
            });

            const tabButtons = document.querySelectorAll('.nav-link');
            tabButtons.forEach(function(tabButton) {
                tabButton.addEventListener('click', function() {
                    localStorage.setItem('activeTab', tabButton.getAttribute('data-bs-target'));
                    scrollToBottom();

                });


            });


            $(function() {
                window.emojiPicker = new EmojiPicker({
                    emojiable_selector: '[data-emojiable=true]',
                    assetsPath: '{{ asset($activeTemplateTrue) }}/images/emoji',
                    popupButtonClasses: 'las la-smile'
                });
                window.emojiPicker.discover();
            });

            const conversationBasePath = "{{ getImage(getFilePath('conversation')) }}";

            const PUSHER_APP_KEY = "{{ gs('pusher_app_key') }}";
            const PUSHER_CLUSTER = "{{ gs('pusher_app_cluster') }}";
            const BASE_URL = "{{ route('home') }}";


            var pusher = new Pusher(PUSHER_APP_KEY, {
                cluster: PUSHER_CLUSTER,

            });
            const pusherConnection = (eventName, channelName) => {
                pusher.connection.bind('connected', () => {
                    const SOCKET_ID = pusher.connection.socket_id;
                    pusher.config.authEndpoint = `${BASE_URL}/pusher/auth/${SOCKET_ID}/${channelName}`;
                    let channel = pusher.subscribe(channelName);
                    channel.bind('pusher:subscription_succeeded', function() {
                        channel.bind(eventName, function(data) {
                            console.log("tut data", data);

                            $('.chat__body').append(
                                `<div class="message-item">
                                    <div class="message-item__wrapper">
                                        <div class="message-item__profile">
                                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $order?->user?->image, getFileSize('userProfile')) }}"
                                                alt="profile-image">
                                        </div>
                                         <p class="message-item__text">${data.conversation.message} <br>${data.conversation.attachment_url ? 
                                            `<a href="${data.conversation.attachment_url}"  download class="attachment-link"> Download File</a>`  : ''}</p>
                                    </div>
                                </div>`
                            )
                            scrollToBottom();

                        })
                    });
                });
            };

            pusherConnection(`provider-message`, "private-conversation-{{ $order->id }}");


            $(document).ready(function() {
                $('.messageForm').on('submit', function(e) {
                    e.preventDefault();

                    var formData = $(this).serialize();
                    var message = $('.message').val();

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: new FormData(this),
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {

                            if (response.success) {
                                $('.chat__body').append(
                                    `<div class="message-item form--message">
                                    <div class="message-item__wrapper">
                                        <p class="message-item__text">${message}
                                             <br>
                                             ${response.data.attachment_url ? `<a href="${response.data.attachment_url}" download class="attachment-link"> Download File</a>` : ''}
                                            
                                            </p>

                                        <div class="message-item__profile">
                                            <img src="{{ getImage(getFilePath('providerProfile') . '/' . $order?->provider?->image, getFileSize('providerProfile')) }}"
                                                alt="profile-image">
                                        </div>
                                    </div>
                                </div>`
                                )
                                $('.message').text('');
                                $('.messageForm').trigger('reset');
                                scrollToBottom();

                            } else {
                                notify('error', response.message);
                            }
                        }
                    });
                });
            });

            scrollToBottom();

            function scrollToBottom() {
                const chatBody = document.getElementById('chatBody');
                chatBody.scrollTop = chatBody.scrollHeight;
            }

        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .no-found-image {
            max-height: 100px;
        }

        .summery-list {
            margin-bottom: 24px;
        }

        .summery-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .summery-list-item:not(:last-child) {
            margin-bottom: 16px
        }

        .summery-list-item__title {
            color: hsl(var(--black));
            font-weight: 500;
            font-size: 16px;
        }

        .summery-list-item__content span {
            font-size: 16px;
        }

        .summery-list-item__price {
            color: hsl(var(--black));
            font-weight: 500;
        }
    </style>
@endpush
