@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    @include('admin.service.tab')
                    <form action="{{ route('admin.service.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label> @lang('Image')</label>
                                        <x-image-uploader class="w-100" type="service" id="main-image" :required=false />
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label> @lang('Cover Image')</label>
                                        <x-image-uploader class="w-100" type="coverImage" name="cover_image" id="cover-image" :required=false />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Name')</label>
                                        <input type="text" class="form-control" name="name" required
                                               value="{{ old('name') }}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Category')</label>
                                        <select class="form-control select2" name="category_id" required>
                                            <option selected disabled>@lang('Select')</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected($category->id == old('category_id'))>
                                                    {{ __($category->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.service.index') }}" />
@endpush
