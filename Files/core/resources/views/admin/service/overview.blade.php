@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    @include('admin.service.tab')
                    <form action="{{ route('admin.service.overview', $service->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 my-3">
                                    <h5>@lang('Service Note')</h5>
                                    <button class="btn btn-sm btn-outline--primary addNote" type="button">
                                        <i class="la la-fw la-plus"></i>@lang('Add New')
                                    </button>
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="@lang('Enter service note here')" class="form-control" name="note[]" id="note" />
                                </div>
                                <div id="noteContainer"></div>
                            </div>

                            <div class="form-group">
                                <label>@lang('Service Overview')</label>
                                <textarea rows="10" class="form-control border-radius-5 nicEdit" id="overview" name="overview">@php echo  old('overview') @endphp</textarea>
                            </div>

                            <div class="form-group">
                                <label>@lang('Details')</label>
                                <textarea rows="10" class="form-control border-radius-5 nicEdit" id="details" name="details">@php echo  old('details') @endphp</textarea>
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
                                <div class="row addedField">
                                    @if (old('faq'))
                                        @foreach (old('faq') as $faq)
                                            @php
                                                $index = $loop->index;
                                            @endphp
                                            <div class="col-md-4 faq-list mb-3">
                                                <span class="bg--danger removeFaqBtn ">
                                                    <i class="las la-times"></i>
                                                </span>
                                                <div class="card faq-data p-2">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <input class="form-control" name="faq[{{ $loop->index }}][title]" type="text" value="{{ $faq['title'] }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea class="form-control" name="faq[{{ $loop->index }}][details]" cols="15" rows="5"> {{ $faq['details'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
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

            var noteAdded = 0;
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
