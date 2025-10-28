@php
    $service_categoryContent = getContent('service_category.content', true);
    $categories = App\Models\Category::active()->get();
    $initialCategory = App\Models\Category::active()
        ->with('services', function ($q) {
            return $q->active()->limit(4);
        })
        ->first();
@endphp

<div class="category-service my-120">
    <div class="container custom-container">
        <div class="section-heading style-left flex-between gap-2">
            <h2 class="section-heading__title">{{ __($service_categoryContent?->data_values?->heading) }}</h2>
            @if ($categories->count() > 1)
                <form class="service-catgory-form">
                    <select name="name" id="service-category" class="form--control form-select select2">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
        @if ($initialCategory)
            <div class="service-content">
                <h4 class="service-content__heading service_title">{{ __($initialCategory->name) }}</h4>
                <div class="row gy-4 service-items-container loadingContent text-center">
                    @foreach ($initialCategory->services ?? [] as $service)
                        <div class="col-xsm-6 col-sm-6 col-lg-4 col-xl-3">
                            <a href="{{ route('service.details', ['slug' => $service->slug]) }}" class="service-item">
                                <div class="service-item__image">
                                    <img src="{{ getImage(getFilePath('service') . '/' . $service->image, getFileSize('service')) }}" alt="@lang('image')">
                                </div>
                                <h5 class="service-item__title">{{ __($service->name) }}</h5>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('style')
    <style>
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loading img {
            width: 100px;
            height: 100px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
    </style>
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";

            function fetchServicesByCategory(categoryId) {
                var url = '{{ route('services.category', ':categoryId') }}';
                url = url.replace(':categoryId', categoryId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        $('.loadingContent').html(`
                            <div class="loading">
                                <img src="{{ asset($activeTemplateTrue . 'images/loading.gif') }}" alt="Loading...">
                            </div>
                        `);
                    },
                    success: function(response) {
                        var serviceItems = response?.category?.services || [];
                        var categoryName = response?.category?.name;
                        var serviceItemsContainer = $('.service-items-container');
                        serviceItemsContainer.empty();

                        $('.service_title').text(categoryName);

                        var basePath = '{{ asset(getFilePath('service')) }}/';
                        $.each(serviceItems, function(index, service) {
                            var serviceDetailsLink =
                                '{{ route('service.details', ['slug' => ':slug']) }}';
                            serviceDetailsLink = serviceDetailsLink.replace(':slug', service.slug);
                            var imageUrl = basePath + service.image;
                            var serviceItem = `
                            <div class="col-xsm-6 col-sm-6 col-lg-4 col-xl-3">
                                <a href="${serviceDetailsLink}" class="service-item">
                                    <div class="service-item__image">
                                        <img src="${imageUrl}" alt="img">
                                    </div>
                                    <h5 class="service-item__title">${service.name}</h5>
                                </a>
                            </div>
                        `;
                            serviceItemsContainer.append(serviceItem);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            $('#service-category').on('change', function() {
                var categoryId = $(this).val();
                fetchServicesByCategory(categoryId);
            });
        })(jQuery);
    </script>
@endpush
