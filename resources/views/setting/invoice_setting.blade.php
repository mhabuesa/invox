@extends('layouts.app')
@section('title', 'Invoice Settings')
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
            <form method="POST" action="{{ route('setting.invoice.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title my-1">
                            Invoice Authorized Information
                        </h3>
                        <span class="float-right">
                            <label class="toggle-switch green">
                                <input class="form-check-input" type="checkbox" name="authorized_status"
                                    id="authorized_status" @if (isset($info->authorized_status) && $info->authorized_status == 1) checked @endif>
                                <span class="slider"></span>
                            </label>
                        </span>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <div class="mb-3">
                                    <label for="name">Authorized Person Name:</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" placeholder="Enter Authorized Person Name"
                                        value="{{ old('name', $info->name ?? '') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <div class="mb-3">
                                    <label for="name">Authorized Person Designation:</label>
                                    <input type="text" class="form-control @error('designation') is-invalid @enderror"
                                        name="designation" id="designation"
                                        placeholder="Enter Authorized Person Designation"
                                        value="{{ old('designation', $info->designation ?? '') }}">
                                    @error('designation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-lg-4 m-auto">

                                <div class="text-center mb-4">
                                    <label class="mb-3 font-weight-bold">Signature</label>
                                    <div class="mb-3" id="signature-wrapper">
                                        @if ($info->signature ?? false)
                                            <img src="{{ asset($info->signature) }}" id="signature" alt="signature Image"
                                                class="shadow border" width="150">
                                        @else
                                            <img src="https://placehold.co/100x100?font=roboto" id="signature"
                                                alt="signature" class="shadow" width="150">
                                        @endif
                                    </div>
                                    @if ($info->signature ?? false)
                                        <div class="text-muted mb-3">
                                            <a href="#" class="btn btn-danger btn-sm mt-2" onclick="removeImage(this)">Remove
                                                Image</a>
                                        </div>
                                    @endif
                                    <div class="custom-file w-75 mx-auto">
                                        <input type="file" class="custom-file-input" name="signature" id="inputImage"
                                            onchange="document.getElementById('signature').src = window.URL.createObjectURL(this.files[0])">
                                        <label class="custom-file-label d-flex" for="inputImage">Choose file</label>
                                    </div>
                                    @error('image')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title my-1">
                            Invoice Terms and Conditions
                        </h3>
                        <span class="float-right">
                            <label class="toggle-switch green">
                                <input class="form-check-input" type="checkbox" name="terms_status" id="terms_status"
                                    @if (isset($info->terms_status) && $info->terms_status == 1) checked @endif>
                                <span class="slider"></span>
                            </label>
                        </span>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="mb-3">
                                    <label for="terms"> Terms and Conditions:</label>
                                    <textarea class="form-control" name="terms" id="terms" rows="5"
                                        placeholder="Enter Invoice Terms and Conditions">{{ old('terms', $info->terms ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <button type="submit" class="btn btn-info mt-5 w-25 d-flex mx-auto justify-content-center">Submit</button>
        </form>
        <!-- /.row -->
        </div>
    </section>
@endsection

@push('footer')
    <script>
        document.getElementById('signature').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('signaturePreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script>
        function removeImage(button) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('remove.signature') }}";
                    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        success: function(data) {
                            if (data.success) {
                                showToast(data.message, "success");

                                // ✅ Remove the image from the DOM
                                document.getElementById('signature-wrapper').innerHTML = `
                                <img src="https://placehold.co/100x100?font=roboto" id="signature"
                                     alt="signature" class="shadow" width="150">
                            `;
                            } else {
                                showToast(data.message, "error");
                            }
                        },
                        error: function(xhr) {
                            showToast("An error occurred: " + xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        }
    </script>
@endpush
