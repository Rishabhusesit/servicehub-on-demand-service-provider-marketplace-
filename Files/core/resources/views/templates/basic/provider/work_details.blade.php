@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="profile-wrapper">
        <div class="profile-content">
            <div class="profile-block">
                <div class="row">
                    <form action="{{ route('provider.work.details') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-12 form-group">
                            <label class="form--label">@lang('Service City')</label>
                            <select class="form--control form-select select2 city-select" name="service_city_id" required>
                                <option value="">@lang('Select City')</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" @selected($city->id == $provider->service_city_id)>{{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="service_area" class="form--label">@lang('Service Area')</label>
                            <select class="form--control select2 service-area-select" name="service_area_id" required>
                                <option value="">@lang('Select Service Area')</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}" data-city="{{ $area->city_id }}"
                                            @selected($area->id == $provider->service_area_id)>
                                        {{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-sm-12 form-group">
                            <label for="telephone" class="form--label">@lang('Service Category')</label>
                            <select class="form--control select2" name="service_category_id" required>
                                <option value=" ">@lang('Select Type')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($category->id == $provider->service_category_id)>
                                        {{ __($category->name) }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn--base">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        "use strict";
        (function($) {

            let allAreas = $('.service-area-select option[data-city]').clone();
            $('.city-select').on('change', function() {
                let selectedCity = $(this).val();
                let $serviceArea = $('.service-area-select');

                $serviceArea.html('<option value="">Select Service Area</option>');

                allAreas.each(function() {
                    if ($(this).data('city') == selectedCity) {
                        $serviceArea.append($(this));
                    }
                });

                if ($serviceArea.hasClass('select2')) {
                    $serviceArea.select2();
                }
            });

            $('.city-select').trigger('change');

        })(jQuery);
    </script>
@endpush
