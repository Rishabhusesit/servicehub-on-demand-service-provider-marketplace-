@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="order-confirmation py-120">
        <div class="container custom-container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="order-card">
                        <div class="order-card__image">
                            <img src="{{ asset($activeTemplateTrue . '/images/success.png') }}" alt="card-image">
                        </div>
                        <div class="content">
                            <h4 class="order-card__title">{{ $order->user->fullname }} @lang('service request is accepted')</h4>
                            <p class="order-card__text">@lang('Order ID') : #{{ $order->order_id }}</p>

                            <div class="order-card__button flex-wrap">
                                <a href="{{ route('provider.service.details', $order->id) }}"
                                    class="btn--md btn btn--base">@lang('Order Details')</a>
                                <a href="{{ route('home') }}" class="btn--md btn btn-outline--base">@lang('Go Home')</a>
                            </div>
                            <div class="order-card__list">
                                <h5 class="title">@lang('What is next :')</h5>
                                <ol class="list-inner">
                                    <li class="list-item">@lang('You’ll gets for confirmation.')</li>
                                    <li class="list-item">@lang('We will monitor the whole service.')</li>
                                    <li class="list-item">@lang('Expert will arrive at your place & ensure the best service.')</li>
                                    <li class="list-item">@lang('So just RELAX. For any further query contact us.')</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
