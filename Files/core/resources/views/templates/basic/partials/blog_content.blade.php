<div class="col-lg-4 col-md-6">
    <div class="blog-item">
        <div class="blog-item__thumb">
            <a href="{{ route('blog.details', $blog->slug) }}" class="blog-item__thumb-link">
                <img src="{{ frontendImage('blog', 'thumb_' . $blog?->data_values?->image, '435x297') }}" class="fit-image" alt="@lang('img')">
            </a>
        </div>
        <div class="blog-item__content">
            <h4 class="blog-item__title"><a href="{{ route('blog.details', $blog->slug) }}"
                   class="blog-item__title-link border-effect">{{ __(strLimit($blog?->data_values?->title, 50)) }}</a></h4>
            <p class="blog-item__desc">{{ __(strLimit(strip_tags($blog?->data_values?->description), 130)) }}</p>
        </div>
    </div>
</div>
