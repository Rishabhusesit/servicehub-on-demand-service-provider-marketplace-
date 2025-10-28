@php
    $categoryContent = getContent('category.content', true);
    $categoriesQuery = App\Models\Category::active();
    $categoryCount = (clone $categoriesQuery)->count();
    $categories = (clone $categoriesQuery)
        ->withCount([
            'services' => function ($q) {
                $q->active();
            },
        ])
        ->limit(11)
        ->get();
@endphp

@if ($categoryCount)
    <section class="category-section my-120">
        <div class="container custom-container">
            <div class="section-heading style-left">
                <h2 class="section-heading__title">{{ __($categoryContent?->data_values?->heading) }}</h2>
            </div>
            <div class="category-wrapper row gy-4">
                @foreach ($categories as $category)
                    <div class="col-xsm-6 col-sm-4 col-lg-3 col-xl-2">
                        <a href="{{ route('service') }}#{{ $category->slug }}" class="category-item">
                            <div class="category-item__icon">
                                <img src="{{ getImage(getFilePath('category') . '/' . $category->image, getFileSize('category')) }}" alt="@lang('img')">
                            </div>
                            <h5 class="category-item__heading">{{ __($category->name) }}</h5>
                            <p class="category-item__count">
                                <span class="number">{{ $category->services_count }}</span>@lang('Services')
                            </p>
                        </a>
                    </div>
                @endforeach
                @if ($categoryCount > 11)
                    <div class="col-xsm-6 col-sm-4 col-lg-3 col-xl-2">
                        <a href="{{ route('service') }}" class="category-item">
                            <div class="category-item__icon category-item__viewicon">
                                <i class="icon-Framede"></i>
                            </div>
                            <h5 class="category-item__heading">@lang('View All Categories')</h5>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif


@push('style')
    <style>
        .category-item__icon img {
            max-height: 60px;
        }
    </style>
@endpush
