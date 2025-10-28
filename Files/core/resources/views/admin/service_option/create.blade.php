@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <form action="{{ route('admin.service.serviceOption.save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label> @lang('Image')</label>
                                    <x-image-uploader class="w-100" type="service" id="main-image" :required=false />
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="form-group">
                                        <label>@lang('Name')</label>
                                        <input type="text" class="form-control" name="name" required value="{{ old('name') }}" />
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Price')</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="price"
                                                   value="{{ old('price') }}" required />
                                            <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>@lang('Parent')</label>
                                        <select class="form-control select2" name="parent_id" id="parent_id">
                                            <option selected disabled>@lang('Select')</option>
                                            @foreach ($parents as $parent)
                                                <option value="{{ $parent->id }}" @selected($parent->id == old('parent_id'))>
                                                    {{ __($parent->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>@lang('Service')</label>
                                        <select class="form-control select2" name="service_id" id="service_id" required>
                                            <option selected disabled>@lang('Select')</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}" @selected($service->id == old('service_id'))>
                                                    {{ __($service->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3">
                            <h5 class="mb-0">@lang('Service Note')</h5>
                            <button class="btn btn-sm btn-outline--primary addNote" type="button">
                                <i class="la la-fw la-plus"></i>@lang('Add New')
                            </button>
                        </div>
                        <div id="noteContainer" class="addedNote">
                            <div class="row note-field">
                                <div class="col-12 my-3">
                                    <div class="group-wrapper d-flex gap-3 w-100">
                                        <input type="text" placeholder="@lang('Enter service note here')" class="form-control" name="note[]" id="note" />
                                        <div>
                                            <button type="button" class="btn btn--danger w-100 h-45 noteDelete"><i class="la la-times ms-1"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.service.serviceOption.index') }}" />
@endpush
@push('script')
    <script>
        (function($) {
            "use strict"
            var noteAdded = $(".addedNote .note-field").length;
            $('.addNote').on('click', function() {
                noteAdded++;
                $("#noteContainer").append(`
                    <div class="row">
                        <div class="col-12 my-3">
                            <div class="group-wrapper d-flex gap-3 w-100">
                                <input type="text" placeholder="@lang('Enter service note here')" class="form-control" name="note[]" id="note"/>
                            <div>
                            <button type="button" class="btn btn--danger w-100 h-45 noteDelete"><i class="la la-times ms-1"></i></button>
                        </div>
                    </div>
                `)
            });

            $(document).on('click', '.noteDelete', function() {
                noteAdded--;
                $(this).closest('.row').remove();
            });

        })(jQuery);
    </script>
@endpush
