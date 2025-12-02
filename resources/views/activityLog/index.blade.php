@extends('layouts.app')
@section('title', 'Activity Log')
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
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">User Activity Log</h3>
                            <div class="float-right d-flex align-items-center p-2 rounded">
                                {{-- <select name="data" id="" class="form-control">
                                                <option value="7">Last 7 Days</option>
                                                <option value="15">Last 15 Days</option>
                                                <option value="30">Last 30 Days</option>
                                                <option value="all">All</option>
                                            </select> --}}
                                <div class="btn-group">
                                    <button type="button" class="btn btn-danger dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown" aria-expanded="false">
                                        <span class="">Log Delete</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item btn mt-1 btn btn-sm" href="#"
                                            onclick="deleteActivity(this)" data-id="1">
                                            <i class="fas fa-trash text-danger mr-1"></i> Older Than 7 Days
                                        </a>
                                        <a class="dropdown-item btn mt-1 btn btn-sm" href="#"
                                            onclick="deleteActivity(this)" data-id="2">
                                            <i class="fas fa-trash text-danger mr-1"></i> Older Than 15 Days
                                        </a>
                                        <a class="dropdown-item btn mt-1 btn btn-sm" href="#"
                                            onclick="deleteActivity(this)" data-id="3">
                                            <i class="fas fa-trash text-danger mr-1"></i> Older Than 30 Days
                                        </a>
                                        <a class="dropdown-item btn mt-1 btn btn-sm" href="#"
                                            onclick="deleteActivity(this)" data-id="4">
                                            <i class="fas fa-trash text-danger mr-1"></i> All Logs
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="activityTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Action</th>
                                        <th>Description</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if (Str::contains(strtolower($log->action), 'delete'))
                                                    <span class="text-danger fw-bold">{{ $log->action }}</span>
                                                @else
                                                    {{ $log->action }}
                                                @endif
                                            </td>

                                            <td>{{ $log->description }}</td>
                                            <td>{{ $log->user->name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
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
            $("#activityTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#activityTable_wrapper .col-md-6:eq(0)');
        });

        // Delete Activity Log Confirmation alart
        function deleteActivity(button) {
            const id = $(button).data('id');

            let text = "";
            if (id == 1) {
                text = "7";
            } else if (id == 2) {
                text = "15";
            } else if (id == 3) {
                text = "30";
            } else if (id == 4) {
                text = "all ";
            }

            Swal.fire({
                title: "Are you sure?",
                text: id == 4 ?
                    "This action cannot be undone." :
                    "Logs older than " + text + " days will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    let url = "{{ route('activityLog.delete', ':id') }}";
                    url = url.replace(':id', id);

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            showToast(data.message, "success");
                            location.reload();
                        },
                        error: function(xhr) {
                            showToast("An error occurred!", "error");
                        }
                    });

                }
            });
        }
    </script>
@endpush
