@extends('layouts.app')
@section('title', 'Taxes')
@push('header')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush
@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Taxes List</h3>
                            <a href="#" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                                data-target="#addTax"> <i class="fas fa-plus"></i> Add New Taxes</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="taxTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Value</th>
                                        <th>Default</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($taxes as $key => $tax)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $tax->name }}
                                            </td>
                                            <td> {{ $tax->value }} %</td>
                                            <td>
                                                <label class="toggle-switch green">
                                                    <input class="form-check-input" type="checkbox"
                                                        {{ $tax->status == 1 ? 'checked' : '' }} name="status"
                                                        data-id="{{ $tax->id }}" data-status="{{ $tax->status }}"
                                                        onchange="updateTaxStatus(this)">
                                                    <span class="slider"></span>
                                                </label>


                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#taxEdit-{{ $tax->id }}"><i
                                                        class="fas fa-edit"></i></a>
                                                <button type="button"
                                                    class="btn btn-sm  btn-danger js-bs-tooltip-enabled mx-2"
                                                    onclick="deleteTax(this)" data-id="{{ $tax->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal for Tax Edit -->
                                        <div class="modal fade" id="taxEdit-{{ $tax->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Tax Edit</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="{{ route('tax.update', $tax->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="name">Name</label>
                                                                    <input type="text" class="form-control"
                                                                        name="name" placeholder="Enter Tax Name"
                                                                        value="{{ $tax->name }}" required>
                                                                    <div class="text-danger mt-1 error-message"></div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="name">Value <span>(%)</span></label>
                                                                    <input type="number" class="form-control"
                                                                        name="value" placeholder="Enter Tax Value"
                                                                        value="{{ $tax->value }}" required>
                                                                    <div class="text-danger mt-1 error-message"></div>
                                                                </div>
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
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal for Add Tax -->
    <div class="modal fade" id="addTax" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Tax</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('tax.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Tax Name">
                            <div class="text-danger mt-1 error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="name">Value <span>(%)</span></label>
                            <input type="number" class="form-control" name="value" placeholder="Enter Tax Value">
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
    <!-- DataTables  & Plugins -->
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

    <!-- Page specific script -->
    <script>
        // DataTable Script
        $(function() {
            $("#taxTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#taxTable_wrapper .col-md-6:eq(0)');
        });

        // Delete Tax Confirmation alart
        function deleteTax(button) {
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

                    let url = "{{ route('tax.destroy', ':id') }}";
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

        // Update Default Tax Status
        function updateTaxStatus(element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Will you change Tax status?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    updateTaxStatusAjax(element);
                } else {
                    element.checked = !element.checked;
                }
            })
        }

        function updateTaxStatusAjax(element) {
            const id = $(element).data('id');
            let url = "{{ route('tax.status.update', ':id') }}";
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
                        // Uncheck all other checkboxes
                        $('input[name="status"]').not(element).prop('checked', false);

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
    </script>
@endpush
