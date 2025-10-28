@php
    $customCaptcha = loadCustomCaptcha();
    $googleCaptcha = loadReCaptcha();
@endphp
@props(['isFrontend' => false])

@if ($googleCaptcha)
    <div class=" {{ $isFrontend ? 'form-group' : 'mb-3' }}">
        @php echo $googleCaptcha @endphp
    </div>
@endif

@if ($customCaptcha)
    <div class="{{ $isFrontend ? 'form-group' : 'mb-3' }}">
        <div class="{{ $isFrontend ? 'mb-3' : 'mb-3' }}">
            @php echo $customCaptcha @endphp
        </div>
        <input type="text" name="captcha" class="form-control form--control" required
            @if ($isFrontend) placeholder="@lang('Enter Captcha')" @endif>
    </div>
@endif
@if ($googleCaptcha)
    @push('script')
        <script>
            (function($) {
                "use strict"
                $('.verify-gcaptcha').on('submit', function() {
                    var response = grecaptcha.getResponse();
                    if (response.length == 0) {
                        document.getElementById('g-recaptcha-error').innerHTML =
                            '<span class="text--danger">@lang('Captcha field is required.')</span>';
                        return false;
                    }
                    return true;
                });

                window.verifyCaptcha = () => {
                    document.getElementById('g-recaptcha-error').innerHTML = '';
                }
            })(jQuery);
        </script>
    @endpush
@endif
