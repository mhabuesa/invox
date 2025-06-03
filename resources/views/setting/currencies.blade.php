@extends('layouts.app')
@section('title', 'Currencies')
@push('header')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush
@section('content')
    @include('setting.menu')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Company Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Currencies</h3>
                            <a href="#" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                                data-target="#addCurrency"> <i class="fas fa-plus"></i> Add New Currency</a>
                        </div>
                        <div class="card-body">
                            <table id="currencyTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Country</th>
                                        <th>Currency</th>
                                        <th>Code</th>
                                        <th>Symbol</th>
                                        <th>Default</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($currencies as $currency)
                                        <tr data-status="{{ $currency->status }}">
                                            <td>{{ $currency->country }}</td>
                                            <td>{{ $currency->currency }}</td>
                                            <td>{{ $currency->code }}</td>
                                            <td>{{ $currency->symbol }}</td>
                                            <td>
                                                <label class="toggle-switch green">
                                                    <input class="form-check-input" type="checkbox"
                                                        {{ $currency->status == 1 ? 'checked' : '' }} name="status"
                                                        data-id="{{ $currency->id }}" onchange="updateCurrencyStatus(this)">
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#currencyEdit-{{ $currency->id }}"><i
                                                        class="fas fa-edit"></i></a>
                                                <button type="button"
                                                    class="btn btn-sm  btn-danger js-bs-tooltip-enabled mx-2"
                                                    onclick="deleteCurrency(this)" data-id="{{ $currency->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal for Currency Edit -->
                                        <div class="modal fade" id="currencyEdit-{{ $currency->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Currency Edit</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST"
                                                        action="{{ route('currency.update', $currency->id) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="country">Country</label>
                                                                <input type="text" id="country" class="form-control"
                                                                    name="country" placeholder="Enter Country Name"
                                                                    value="{{ $currency->country }}" required>
                                                                <div class="text-danger mt-1 error-message"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="currency">Currency <small> (eg. US Dollar)
                                                                    </small> </label>
                                                                <input type="text" id="currency" class="form-control"
                                                                    name="currency" placeholder="Enter Currency Name"
                                                                    value="{{ $currency->currency }}">
                                                                <div class="text-danger mt-1 error-message"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="code">Code <small class="text-muted">(eg.
                                                                        USD)</small></label>
                                                                <input type="text" id="code" class="form-control"
                                                                    name="code" placeholder="Enter Code"
                                                                    value="{{ $currency->code }}">
                                                                <div class="text-danger mt-1 error-message"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="symbol">Symbol <small class="text-muted">(eg.
                                                                        $)</small></label>
                                                                <input type="text" id="symbol" class="form-control"
                                                                    name="symbol" placeholder="Enter Symbol"
                                                                    value="{{ $currency->symbol }}" required>
                                                                <div class="text-danger mt-1 error-message"></div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Close</button>
                                                            <button class="btn btn-info" type="submit">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal for Add Currency -->
    <div class="modal fade" id="addCurrency" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Currency</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('currency.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" class="form-control" name="country"
                                placeholder="Enter Country Name" required>
                            <div class="text-danger mt-1 error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="currency">Currency <small> (eg. US Dollar)
                                </small> </label>
                            <input type="text" id="currency" class="form-control" name="currency"
                                placeholder="Enter Currency Name">
                            <div class="text-danger mt-1 error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="code">Code <small class="text-muted">(eg.
                                    USD)</small></label>
                            <input type="text" id="code" class="form-control" name="code"
                                placeholder="Enter Code">
                            <div class="text-danger mt-1 error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="symbol">Symbol <small class="text-muted">(eg.
                                    $)</small></label>
                            <input type="text" id="symbol" class="form-control" name="symbol"
                                placeholder="Enter Symbol" required>
                            <div class="text-danger mt-1 error-message"></div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-info" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('assets') }}/dist/js/pages/invoice.js"></script>

    <script src="{{ asset('assets') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/jszip/jszip.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <script>
        $(function() {
            $("#currencyTable").DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: true,
                ordering: false, // 🟢 Turn off all default sorting
                columnDefs: [{
                    targets: [1],
                    orderable: false // Column index 1 will not be orderable (optional here)
                }]
            }).buttons().container().appendTo('#currencyTable_wrapper .col-md-12:eq(0)');
        });
    </script>

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

    <script>
        // Update Default Currency Status
        function updateCurrencyStatus(element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Will you change Currency status?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    updateCurrencyStatusAjax(element);
                } else {
                    element.checked = !element.checked;
                }
            })
        }

        function updateCurrencyStatusAjax(element) {
            const id = $(element).data('id');
            let url = "{{ route('currency.status.update', ':id') }}";
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        // ✅ Use DataTable API to uncheck from all pages, not just visible
                        let table = $('#currencyTable').DataTable();

                        // Loop through ALL rows
                        table.rows().every(function() {
                            let row = this.node();
                            let checkbox = $(row).find('input[name="status"]');
                            if (checkbox.data('id') != id) {
                                checkbox.prop('checked', false);
                            }
                        });

                        showToast(data.message, "success");
                    } else {
                        showToast(data.message, "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.log('xhr.responseText, status, error', xhr.responseText, status, error);
                    showToast('Something went wrong', "error");
                }
            });
        }

        // Delete currency Confirmation alart
        function deleteCurrency(button) {
            const id = $(button).data('id');
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

                    let url = "{{ route('currency.destroy', ':id') }}";
                    url = url.replace(':id', id);
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
                                $(button).closest('tr').remove();
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
