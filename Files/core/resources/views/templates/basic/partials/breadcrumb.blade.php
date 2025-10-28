@php
    $breadcrumbContent = getContent('breadcrumb.content', true);
@endphp
<div class="breadcrumb py-60 bg-img" data-background-image="{{ frontendImage('breadcrumb', $breadcrumbContent->data_values->image ?? '') }}">
    <div class="container custom-container">
        <h2 class="breadcrumb__title">{{ isset($pageTitle) ? __($pageTitle) : '' }}</h2>
    </div>
</div>
