@extends('admin.layouts.app')
@section('panel')


    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <form action="{{ route('admin.service.serviceOption.overview', $service_option->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @include('admin.service_option.tab')

                        <div class="card mb-3 mt-3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card border--primary ">
                                        <h5 class="card-header bg--primary  text-white">@lang('Service Note')
                                            <button class="btn btn-sm btn-outline-light float-end addNote"
                                                type="button"><i
                                                    class="la la-fw la-plus"></i>@lang('Add New')
                                            </button>
                                        </h5>
          
                                        <div class="card-body">
                                            <div class="form-group">
                                                <input type="text" placeholder="@lang('Enter service note here')"
                                                    class="form-control" name="note[]" id="note" />
                                            </div>
                                            <div id="noteContainer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
          
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label>@lang('Service Overview')</label>
                                <textarea rows="10" class="form-control border-radius-5 nicEdit" name="overview">{{ old('overview') }}</textarea>
                            </div>
                        </div>
          
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label>@lang('Details')</label>
                                <textarea rows="10" class="form-control border-radius-5 nicEdit" name="details">{{ old('details') }}</textarea>
                            </div>
                        </div>
          
                        <div class="card mb-3 mt-3">
                            <div class="payment-method-body ">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card border--primary ">
                                            <h5 class="card-header bg--primary  text-white">@lang('Service faqs')
                                                <button class="btn btn-sm btn-outline-light float-end addFaqsData"
                                                    type="button"><i class="la la-fw la-plus"></i>@lang('Add New')
                                                </button>
                                            </h5>
          
                                            <div class="card-body">
                                                <div class="row addedField">
                                                    @if (old('faq'))
                                                    @foreach (old('faq') as $faq)
                                                    @php
                                                    $index = $loop->index;
                                                    @endphp
                                                    <div class="col-md-4  faq-list mb-3">
                                                        <span class="bg--danger removeFaqBtn ">
                                                            <i class="las la-times"></i>
                                                        </span>
                                                        <div class="card faq-data p-2">
                                                            <div class="card-body">
                                                                <div class="input-group mb-3">
                                                                    <input class="form-control"
                                                                        name="faq[{{ $loop->index }}][title]"
                                                                        type="text" value="{{ $faq['title'] }}">
          
                                                                </div>
                                                                <div class="form-group">
                                                                    <textarea class="form-control" name="faq[{{ $loop->index }}][details]" cols="15" rows="5"> {{ $faq['details'] }}
                                                                    </textarea>
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
            let faqCount = 1;
            @if (@old('faq'))
                let index = "{{ $index }}"
                faqCount = parseInt(faqCount) + parseInt(index);
            @endif

            $('.addFaqsData').on('click', function() {
                let html = `<div class="col-md-4  faq-list mb-3">
						<button class="btn btn--danger removeFaqBtn ">
							<i class="las la-times"></i>
						</button>
						<div class="card faq-data p-2">
                                       <div class="card-body">
                                    <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="faq[${faqCount}][title]" placeholder="@lang('Title')">

                        	</div>
                              <div class="form-group">
                                    <textarea name="faq[${faqCount}][details]" cols="15" class="form-control" rows="5" placeholder="@lang('Faq Description')"></textarea>
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
    <x-back route="{{ route('admin.service.serviceOption.index') }}" />
@endpush
