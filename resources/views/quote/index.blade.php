@extends('layouts.app')
@section('title', 'Quotes')
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
                            <h3 class="card-title  ">Quote List</h3>
                            @can('quote_add')
                                <a href="{{ route('quote.create') }}" class="btn btn-primary btn-sm float-right"> <i
                                        class="fas fa-plus"></i> Add Quote</a>
                            @endcan
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="quoteTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Client</th>
                                        <th>Quote Date</th>
                                        <th>Amount</th>
                                        @php
                                            $user = Auth::user();
                                        @endphp

                                        @if ($user->can('quote_to_invoice') || $user->can('quote_edit') || $user->can('quote_delete'))
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotes as $key => $quote)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if ($quote->client)
                                                    <span>{{ $quote->client->name }}</span>
                                                    <span class="text-muted d-block">{{ $quote->client->email }}</span>
                                                @else
                                                    <span class="text-danger">Client Not Found</span>
                                                @endif
                                            </td>
                                            <td>{{ $quote->quote_date->format('m/d/Y') }}</td>
                                            <td> {{ currency($quote->total) }}</td>
                                            @if ($user->can('quote_to_invoice') || $user->can('quote_edit') || $user->can('quote_delete'))
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button"
                                                            class="btn btn-default dropdown-toggle dropdown-icon"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        </button>
                                                        <div class="dropdown-menu" role="menu" style="">
                                                            @can('quote_to_invoice')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('quote.convertToInvoice', $quote->id) }}"
                                                                    onclick="return convertToInvoice(event)"
                                                                    data-id="{{ $quote->id }}">
                                                                    <i class="fas fa-file-invoice fa-sm"></i> Convert to Invoice
                                                                </a>
                                                            @endcan

                                                            @can('quote_edit')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('quote.edit', $quote->id) }}"><i
                                                                        class="fas fa-edit fa-sm"></i> Edit</a>
                                                            @endcan

                                                            @can('quote_delete')
                                                                <a class="dropdown-item text-danger" href="#"
                                                                    onclick="deleteQuote(this)" data-id="{{ $quote->id }}">
                                                                    <i class="fas fa-trash fa-sm"></i> Delete</a>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </td>
                                            @endcan
                                    </tr>
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

@endsection

@push('footer')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('assets') }}/plugins/jszip/jszip.min.js"></script>
<script src="{{ asset('assets') }}/plugins/pdfmake/pdfmake.min.js"></script>
<script src="{{ asset('assets') }}/plugins/pdfmake/vfs_fonts.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- Page specific script -->
<script>
    // DataTable Script
    $(function() {
        $("#quoteTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#quoteTable_wrapper .col-md-6:eq(0)');
    });

    // Delete Quote Confirmation alart
    function deleteQuote(button) {
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

                let url = "{{ route('quote.destroy', ':id') }}";
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

    function convertToInvoice(event) {
        event.preventDefault(); // prevent default navigation

        const url = event.currentTarget.getAttribute('href');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Transfer it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // proceed with redirect
                window.location.href = url;
            }
        });

        return false;
    }
</script>
@endpush
