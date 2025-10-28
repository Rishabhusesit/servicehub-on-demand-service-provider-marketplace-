@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>

                                    <th>@lang('Name')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Is Featured')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($services as $service)
                                    <tr>

                                        <td>
                                            <div class="user">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('service') . '/' . $service->image, getFileSize('service')) }}"
                                                         class="plugin_bg">
                                                </div>
                                                <span class="name">{{ __(Str::limit($service->name, 20)) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            {{ __(str::limit($service?->category?->name, 20)) }}
                                        </td>
                                        <td>
                                            @if ($service->is_featured == Status::ENABLE)
                                                <span class="badge badge--info">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--dark">@lang('No')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                echo $service->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                @if ($service->is_featured == Status::DISABLE)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline--info confirmationBtn"
                                                            data-action="{{ route('admin.service.featured', $service->id) }}"
                                                            data-question="@lang('Are you sure to feature this service?')">
                                                        <i class="las la-star"></i> @lang('Feature')
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline--dark confirmationBtn"
                                                            data-action="{{ route('admin.service.featured', $service->id) }}"
                                                            data-question="@lang('Are you sure to unfeature this service?')">
                                                        <i class="las la-star-half-alt"></i> @lang('Unfeature')
                                                    </button>
                                                @endif

                                                <a href="{{ route('admin.service.edit', $service->id) }}"
                                                   class="btn btn-sm btn-outline--primary"><i class="las la-pen"></i>
                                                    @lang('Edit')</a>

                                                @if ($service->status == Status::DISABLE)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline--success confirmationBtn"
                                                            data-action="{{ route('admin.service.status', $service->id) }}"
                                                            data-question="@lang('Are you sure to enable this service?')">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline--danger confirmationBtn"
                                                            data-action="{{ route('admin.service.status', $service->id) }}"
                                                            data-question="@lang('Are you sure to disable this service?')">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-empty-message />
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($services->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($services) }}
                    </div>
                @endif
            </div>
        </div>
        <div class="modal fade modal-lg" id="customModal" role="dialog" tabindex="-1">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.orderStep.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body addedField">
                            <div class="d-flex justify-content-between align-items-center my-3">
                                <h5>@lang('All Steps')</h5>
                                <button class="btn btn-sm btn-outline--primary addNote" type="button"><i class="la la-plus"></i>@lang('Add New')</button>
                            </div>
                            @foreach (gs('order_steps') ?? [] as $index => $step)
                                <div id="step{{ $loop->index }}" class="noteWrapper step-list">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">@lang('Step') {{ $loop->index + 1 }} : </h6>
                                        <span class="remove-button noteDelete"><i class="las la-times-circle"></i></span>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Title')</label>
                                        <input class="form-control" type="text" name="title[]" value="{{ isset($step['title']) ? $step['title'] : '' }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Description')</label>
                                        <input type="text" class="form-control" name="description[]" value="{{ isset($step['description']) ? $step['description'] : '' }}" required>
                                    </div>
                                </div>
                            @endforeach
                            <div id="noteContainer"></div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search by service..." />
    <a href="{{ route('admin.service.create') }}" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Add New')</a>
    <button type="button" class="btn btn-sm btn-outline--dark customModalBtn" data-modal_title="@lang('How to order')"><i class="las la-sort-numeric-down"></i>@lang('Order Process')</button>
@endpush

@push('style')
    <style>
        .step-list {
            border-bottom: 1px dashed #e4e4e4;
            padding: 15px 0;
        }

        .noteDelete {
            background: #eb2222;
            text-align: center;
            padding: 3px 6px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"

            var noteAdded = $(".addedField .step-list").length;
            $('.addNote').on('click', function() {
                noteAdded++;
                var uniqueId = 'note_' + noteAdded;

                $("#noteContainer").append(`
                    <div id="${uniqueId}" class="noteWrapper step-list">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">@lang('Step') ${noteAdded} : </h6>
                            <span class="remove-button noteDelete"><i class="las la-times-circle"></i></span>
                        </div>
                        <div class="form-group">
                            <label>@lang('Title')<span class="text--danger">*</span></label>
                            <input class="form-control" type="text" name="title[]" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Description')<span class="text--danger">*</span></label>
                            <input class="form-control" type="text" name="description[]" required>
                        </div>
                    </div>
                    `);
                reIndexSteps();
            });

            $(document).on('click', '.noteDelete', function() {
                $(this).closest('.noteWrapper').remove();
                reIndexSteps();
            });

            function reIndexSteps() {
                $('.noteWrapper').each(function(index) {
                    $(this).find('h6').text('@lang('Step') ' + (index + 1) + ' :');
                });
            }

        })(jQuery);
    </script>
@endpush
