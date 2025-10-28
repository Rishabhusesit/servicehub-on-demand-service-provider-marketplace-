<div class="service-package-content">
    <div class="service-package-item parent">
        <div class="wrapper">
            <div class="item-right">
                <a href="javascript:void(0)" class="btn btn-outline--base backBtn" data-option="{{ $service->id }}">
                    <span class="icon"><i class="las la-arrow-left"></i></span> {{ __($service->name) }}
                </a>
            </div>
        </div>
    </div>

    @foreach ($options as $option)
        <div class="service-package-item parent">
            <div class="wrapper">
                <div class="item-left">
                    @if ($option->image)
                        <div class="image">
                            <img src="{{ getImage(getFilePath('serviceOption') . '/' . $option->image) }}"
                                 alt="package-image">
                        </div>
                    @endif
                    <div class="content">
                        <h6 class="title">{{ $option->name }}</h6>
                    </div>
                </div>

                <div class="item-right">
                    @php
                        $offer = $service->service->offers->first();
                        $discountPrice = $originalPrice = $option->price;

                        if ($offer) {
                            if ($offer->discount_type == Status::DISCOUNT_PERCENT && $offer->amount > 0) {
                                $discountPrice = $originalPrice - $originalPrice * ($offer->amount / 100);
                            } elseif ($offer->discount_type == Status::DISCOUNT_FIXED && $offer->amount > 0) {
                                $discountPrice = $originalPrice - $offer->amount;
                            }
                        }
                    @endphp

                    @if ($option->child()->count() > 0)
                        <a href="javascript:void(0)" class="btn btn-outline--base fs-18 nextBtn" data-option="{{ $option->id }}">
                            {{ trans('Next') }} <span class="icon"><i class="las la-arrow-right"></i></span>
                        </a>
                        @if ($originalPrice != 0)
                            @if ($discountPrice != $originalPrice)
                                <p class="package-item-price">
                                    @lang('Starts from')
                                    <span class="old-price">{{ showAmount($originalPrice) }}</span>
                                    <span class="price discount-price">{{ showAmount($discountPrice) }}</span>
                                </p>
                            @else
                                <p class="package-item-price">
                                    @lang('Starts from')
                                    <span class="price">{{ showAmount($discountPrice) }}</span>
                                </p>
                            @endif
                        @endif
                    @else
                        <a href="javascript:void(0)" class="btn btn-outline--base addBtn"
                           data-option='{{ json_encode(['service' => $service, 'option' => $option, 'price' => $discountPrice > 0 ? $discountPrice : $option->price]) }}'>
                            <span class="icon"><i class="las la-plus"></i></span> {{ trans('Add') }}
                        </a>
                        @if ($originalPrice != 0)
                            @if ($discountPrice != $originalPrice)
                                <p class="package-item-price">
                                    @lang('Starts from')
                                    <span class="old-price">{{ showAmount($originalPrice) }}</span>
                                    <span class="price discount-price">{{ showAmount($discountPrice) }}</span>
                                </p>
                            @else
                                <p class="package-item-price">
                                    @lang('Starts from')
                                    <span class="price">{{ showAmount($discountPrice) }}</span>
                                </p>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
