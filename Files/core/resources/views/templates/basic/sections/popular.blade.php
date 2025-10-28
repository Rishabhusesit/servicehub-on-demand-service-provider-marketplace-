@php
    $popularContent = getContent('popular.content', true);
    $popularServices = App\Models\Service::active()->where('is_featured', Status::YES)->limit(4)->get();
@endphp

@if (count($popularServices) > 0)
    <div class="popular-service my-120">
        <div class="container custom-container">
            <div class="section-heading style-left flex-between">
                <h2 class="section-heading__title">{{ __($popularContent?->data_values?->heading) }}</h2>
            </div>
            <div class="service-content">
                <div class="row gy-4">
                    @foreach ($popularServices as $service)
                        <div class="col-xsm-6 col-sm-6 col-lg-4 col-xl-3">
                            <a href="{{ route('service.details', ['slug' => $service->slug]) }}" class="service-item">
                                <div class="service-item__image">
                                    <img src="{{ getImage(getFilePath('service') . '/' . $service->image) }}"
                                         alt="@lang('img')">
                                </div>
                                <h5 class="service-item__title">{{ __($service->name) }}</h5>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
