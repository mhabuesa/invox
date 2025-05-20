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
            <!-- form start -->
            <form method="POST" action="{{ route('setting.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Company Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="mb-0">Currencies</h4>
                            </div>
                            <div class="card-body">
                                <table id="currencyTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Country</th>
                                            <th>Currency</th>
                                            <th>Code</th>
                                            <th>Symbol</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($currencies as $currency)
                                            <tr>
                                                <td>{{ $currency->country }}</td>
                                                <td>{{ $currency->currency }}</td>
                                                <td>{{ $currency->code }}</td>
                                                <td>{{ $currency->symbol }}</td>
                                                <td>
                                                    <a href="" class="btn btn-sm btn-info">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true,
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
@endpush
