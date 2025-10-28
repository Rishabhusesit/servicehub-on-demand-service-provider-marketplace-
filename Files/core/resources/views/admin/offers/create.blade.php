@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.promotion.offer.store', $offer->id ?? 0) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('Offer Name')</label>
                                    <input type="text" class="form-control" name="offer_name" value="{{ old('offer_name', isset($offer) ? $offer->name : '') }}" placeholder="@lang('Type Here')..." required />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Discount Type')</label>
                                    <select class="form-control select2" name="discount_type" data-minimum-results-for-search="-1" required>
                                        <option value="">@lang('Select Discount Type')</option>
                                        <option value="1" {{ old('discount_type', (isset($offer) ? $offer->discount_type : '')) == '1' ? 'selected' : '' }}>
                                            @lang('Fixed')
                                        </option>
                                        <option value="2" {{ old('discount_type', (isset($offer) ? $offer->discount_type : '')) == '2' ? 'selected' : '' }}>
                                            @lang('Percentage')
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Amount')</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="amount" value="{{ old('amount', isset($offer) ? getAmount($offer->amount) : '') }}" placeholder="@lang('Type Here')..." required>
                                        <span class="input-group-text">
                                            <span id="discount_type_text">
                                                {{ isset($offer) ? ($offer->discount_type == 1 ? gs()->cur_text : '%') : '' }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Start Date')</label>
                                    <input type="text" name="start_date" class="datepicker-here form-control" data-language='en' data-format="yyyy-mm-dd" data-position='bottom left' value="{{ old('start_date', isset($offer) ? showDateTime($offer->start_date, 'y-m-d') : '') }}" placeholder="@lang('Select Date')" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('End Date')</label>
                                    <input type="text" name="end_date" class="datepicker-here form-control" data-language='en' data-format="yyyy-mm-dd" data-position='bottom left' value="{{ old('end_date', isset($offer) ? showDateTime($offer->end_date, 'y-m-d') : '') }}" placeholder="@lang('Select Date')" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group select2-parent position-relative">
                                    <label>@lang('Select Services')</label>
                                    <select class="form-control select2 select2-auto-tokenize" name="services[]" multiple required>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}" {{ isset($offer) && $offer->services->contains('id', $service->id) ? 'selected' : '' }}>
                                                {{ __($service->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea class="form-control" name="description" rows="3" required>{{ old('description', isset($offer) ? $offer->description : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.promotion.offer.index') }}" />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/datepicker.min.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"

            $(document).ready(function() {

                $(document).on('change', '#discount_type', function() {
                    var discountType = $(this).val();
                    var textElement = $('#discount_type_text');
                    if (discountType == 1) {
                        textElement.text('{{ gs()->cur_text }}');
                    } else if (discountType == 2) {
                        textElement.text('%');
                    } else {
                        textElement.text('');
                    }
                });
            });

        })(jQuery)
    </script>
@endpush
