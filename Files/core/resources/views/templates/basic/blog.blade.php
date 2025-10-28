@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog my-120">
        <div class="container custom-container">
            <div class="row gy-4 justify-content-center">
                @foreach ($blogs as $blog)
                    @include($activeTemplate . '.partials.blog_content')
                @endforeach
            </div>
            @if ($blogs->hasPages())
                <div class="mt-3">
                    @php echo paginateLinks($blogs) @endphp
                </div>
            @endif
        </div>
    </section>

    @if (isset($sections->secs) && $sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
