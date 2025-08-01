@extends('layouts.app')
@section('title', 'Invoices')
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
                            <h3 class="card-title">Invoice List</h3>
                            @can('invoice_add')
                                <a href="{{ route('invoice.create') }}" class="btn btn-primary btn-sm float-right"> <i
                                        class="fas fa-plus"></i> Add Invoice</a>
                            @endcan
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="invoiceTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Client</th>
                                        <th>Invoice Date</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $key => $invoice)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if ($invoice->client)
                                                    <span>{{ $invoice->client->name }} &nbsp; <a href="#"
                                                            class="badge badge-primary-alt">{{ $invoice->invoice_number }}</a></span>
                                                    <span class="text-muted d-block">{{ $invoice->client->email }}</span>
                                                @else
                                                    <span class="text-danger">Client Not Found</span>
                                                @endif
                                            </td>
                                            <td>{{ $invoice->invoice_date->format('m/d/Y') }}</td>
                                            <td> <strong> {{ currency($invoice->total) }}</strong></td>
                                            <td> <strong class="text-success">
                                                    {{ currency($invoice->payment->sum('amount')) }}</strong></td>
                                            <td> <strong class="text-danger">
                                                    {{ currency($invoice->total - $invoice->payment->sum('amount')) }}</strong>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-default dropdown-toggle dropdown-icon"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        <span class="">Action</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu" style="">
                                                        @can('invoice_edit')
                                                            <a class="dropdown-item"
                                                                href="{{ route('invoice.edit', $invoice->id) }}"><i
                                                                    class="fas fa-edit fa-sm"></i> Edit</a>
                                                        @endcan
                                                        @can('invoice_payment')
                                                        <a class="dropdown-item"
                                                            href="{{ route('invoice.payment', $invoice->id) }}">
                                                            <i class="far fa-credit-card"></i> Payment</a>
                                                        @endcan


                                                        <div class="dropdown-item d-flex justify-content-between">
                                                            <span>
                                                                <a target="_blank"
                                                                    href="{{ route('invoice.show', $invoice->invoice_number) }}">
                                                                    <i class="fas fa-link fa-sm"></i> Invoice
                                                                </a>
                                                            </span>
                                                            <span class="cursor-pointer"
                                                                onclick="copyToClipboard('{{ route('invoice.show', $invoice->invoice_number) }}')"><i
                                                                    class="fas fa-copy"></i></span>
                                                        </div>
                                                        @can('invoice_delete')
                                                        <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item text-danger" href="#"
                                                                onclick="deleteInvoice(this)" data-id="{{ $invoice->id }}"> <i
                                                                    class="fas fa-trash fa-sm"></i> Delete</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
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
            $("#invoiceTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#invoiceTable_wrapper .col-md-6:eq(0)');
        });

        // Delete Invoice Confirmation alart
        function deleteInvoice(button) {
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

                    let url = "{{ route('invoice.destroy', ':id') }}";
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

    <script>
        function copyToClipboard(text) {
            const tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            showToast('Copied to clipboard', 'success');
        }
    </script>
@endpush
