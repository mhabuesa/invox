@extends('layouts.app')
@section('title', 'Settings')
@push('header')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush
@section('content')
@include('setting.menu')

    <section class="content">
        <div class="container-fluid">
            <!-- form start -->
            <form method="POST" action="{{ route('setting.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Company Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="mb-0">Company Information</h4>
                            </div>
                            <div class="card-body row">
                                <div class="form-group col-md-6">
                                    <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control"
                                        value="{{ $setting->company_name }}" placeholder="Enter your company name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control"
                                        placeholder="Enter your company phone" value="{{ $setting->phone }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Enter your company email" value="{{ $setting->email }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control"
                                        placeholder="Enter your company address" value="{{ $setting->address }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Company Logo Upload -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="mb-0">Company Logos</h4>
                            </div>
                            <div class="card-body row">
                                <!-- Website Header Logo -->
                                <div class="col-md-6 text-center mb-4">
                                    <h6>Website Header Logo</h6>
                                    <div class="mb-3">
                                        @if ($setting->logo)
                                            <img id="view-website-logo" src="{{ asset($setting->logo) }}"
                                                alt="Website Logo" height="100">
                                        @else
                                            <img id="view-website-logo" src="https://placehold.co/500x500"
                                                alt="Website Logo" height="100">
                                        @endif
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="logo" id="website-logo"
                                            class="custom-file-input image-input" data-image-id="view-website-logo"
                                            accept="image/*">
                                        <label class="custom-file-label" for="website-logo">Choose file</label>
                                    </div>
                                </div>

                                <!-- Favicon -->
                                <div class="col-md-6 text-center mb-4">
                                    <h6>Favicon</h6>
                                    <div class="mb-3">
                                        @if ($setting->favicon)
                                        <img id="view-invoice-logo" src="{{ asset($setting->favicon) }}" alt="Invoice Logo"
                                        height="100">
                                        @else
                                        <img id="view-invoice-logo" src="https://placehold.co/500x500" alt="Invoice Logo"
                                        height="100">
                                        @endif
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="favicon" id="invoice-logo"
                                            class="custom-file-input image-input" data-image-id="view-invoice-logo"
                                            accept="image/*">
                                        <label class="custom-file-label" for="invoice-logo">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Application Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="mb-0">App Settings</h4>
                            </div>
                            <div class="card-body row">
                                <div class="form-group col-md-4">
                                    <label for="app_url">App URL</label>
                                    <input type="text" name="app_url" class="form-control" id="app_url"
                                        placeholder="Enter your app url" value="{{ $setting->app_url }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="debug">Debug Mode</label>
                                    <select class="form-control" name="debug_mode">
                                        <option {{ $setting->debug_mode == 'true' ? 'selected' : '' }} value="true">True</option>
                                        <option {{ $setting->debug_mode == 'false' ? 'selected' : '' }} value="false">False</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="time_zone">Time zone</label>
                                    <select class="form-control select2" name="time_zone">
                                        @foreach ($time_zones as $time_zone)
                                            <option {{ $setting->time_zone == $time_zone->timezone ? 'selected' : '' }}
                                                value="{{ $time_zone->timezone }}">{{ $time_zone->country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5">Update</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /.row -->
        </div>
    </section>
@endsection

@push('footer')
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('assets') }}/dist/js/pages/invoice.js"></script>
    <script>
        document.querySelectorAll('.image-input').forEach(input => {
            input.addEventListener('change', function(e) {
                const targetId = this.dataset.imageId;
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(targetId).src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush
