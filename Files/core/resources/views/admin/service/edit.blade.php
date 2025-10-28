@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <form action="{{ route('admin.service.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label> @lang('Image')</label>
                                    <x-image-uploader image="{{ $service->image }}" class="w-100" type="service" id="main-image" :required=false />
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label> @lang('Cover Image')</label>
                                    <x-image-uploader image="{{ $service->cover_image }}" name="cover_image" class="w-100" type="coverImage" name="cover_image" id="cover-image" :required=false />
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-xl-6 col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                            <input type="text" class="form-control" name="name" value="{{ $service->name }}" required value="{{ old('name') }}" />
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Category')</label>
                                            <select class="form-control select2" name="category_id" required>
                                                <option selected disabled>@lang('Select')</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @selected($category->id == $service->category_id)>
                                                        {{ __($category->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 my-3">
                                                <h5>@lang('Service Note')</h5>
                                                <button class="btn btn-sm btn-outline--primary addNote" type="button">
                                                    <i class="la la-fw la-plus"></i>@lang('Add New')
                                                </button>
                                            </div>
                                            <div id="noteContainer" class="addedNote">
                                                @foreach (explode(', ', $service->note) as $note)
                                                    <div class="row note-field">
                                                        <div class="col-12 my-3">
                                                            <div class="group-wrapper d-flex gap-3 w-100">
                                                                <input type="text" value="{{ $note }}" class="form-control" name="note[]" id="note" />
                                                                <div>
                                                                    <button type="button" class="btn btn--danger w-100 h-45 noteDelete">
                                                                        <i class="la la-times ms-1"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>@lang('Service overview')</label>
                                        <textarea rows="10" class="form-control border-radius-5 nicEdit" name="overview" required> @php echo $service->overview @endphp</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>@lang('Details')</label>
                                        <textarea rows="10" class="form-control border-radius-5 nicEdit" name="details" required> @php echo $service->details @endphp</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 my-3">
                                    <h5>@lang('Service faqs')</h5>
                                    <button class="btn btn-sm btn-outline--primary addFaqsData" type="button">
                                        <i class="la la-fw la-plus"></i>@lang('Add New')
                                    </button>
                                </div>
                                <div class="row addedField gy-3">
                                    @if ($service->faqs)
                                        @foreach ($service->faqs as $faq)
                                            <div class="col-md-4  faq-list">
                                                <button class="bg--danger removeFaqBtn ">
                                                    <i class="las la-times"></i>
                                                </button>
                                                <div class="card faq-data p-2">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>@lang('Question')</label>
                                                            <input class="form-control" name="faq[{{ $loop->iteration }}][title]" type="text" value="{{ $faq['title'] }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>@lang('Answer')</label>
                                                            <textarea class="form-control" name="faq[{{ $loop->iteration }}][details]" cols="15" rows="5" placeholder="@lang('Faq Description')">{{ $faq['description'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
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
@endsection

@push('style')
    <style>
        .bg--danger {
            margin-top: -1px;
            margin-bottom: -1px;
        }

        .faq-list {
            position: relative;
        }

        .removeFaqBtn {
            right: 0px;
            top: -10px;
            z-index: 100;
            position: absolute;
            padding: 8px;
            border-radius: 52% !important;
            line-height: 1;
        }

        button.btn.btn--danger.removeFaqBtn i {
            margin: auto;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"
            let faqCount = $(".addedField.faq-list").length;
            $('.addFaqsData').on('click', function() {
                let html = `<div class="col-md-4  faq-list mb-3">
						<button class="btn btn--danger removeFaqBtn ">
							<i class="las la-times"></i>
						</button>
					    <div class="card faq-data p-2">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>@lang('Question')</label>
                                    <input class="form-control" name="faq[${faqCount}][title]" type="text">
                                </div>
                                <div class="form-group">
                                    <label>@lang('Answer')</label>
                                    <textarea class="form-control" name="faq[${faqCount}][details]" cols="15" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    `
                $('.addedField').append(html);
                faqCount += 1;
            })

            $(document).on('click', '.removeFaqBtn', function() {
                $(this).closest('.faq-list').remove();
            });

            $(document).on('click', '.removeFaqBtn', function() {
                $(this).closest('.faq-list').remove();
            });

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

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.service.index') }}" />
@endpush
