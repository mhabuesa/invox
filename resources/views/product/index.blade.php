@extends('layouts.app')
@section('title', 'Products')
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
                            <h3 class="card-title">Product List</h3>
                            <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm float-right"> <i
                                    class="fas fa-plus"></i> Add New Product</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="productTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Code</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if ($product->image)
                                                    <img src="{{ asset($product->image) }}" class="img-circle elevation-2"
                                                        width="35" alt="product Image">
                                                @else
                                                    <img src="https://placehold.co/100x100?font=roboto"
                                                        class="img-circle elevation-2" width="35" alt="product Image">
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" class="cursor-pointer" data-toggle="modal"
                                                    data-target="#productView-{{ $product->id }}">{{ $product->name }}</a>
                                            </td>
                                            <td> {{ $product->quantity }}</td>
                                            <td> {{ $product->code }}</td>
                                            <td> {{ $product->category->name }}</td>
                                            <td> {{ $product->unit_price }}</td>
                                            <td>
                                                <a href="{{ route('product.edit', $product->id) }}"
                                                    class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                <button type="button"
                                                    class="btn btn-sm  btn-danger js-bs-tooltip-enabled mx-2"
                                                    onclick="deleteProduct(this)" data-id="{{ $product->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal for View product -->
                                        <div class="modal fade" id="productView-{{ $product->id }}" style="display: none;"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Product View</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group text-center mb-4">
                                                                    <div class="mb-3">
                                                                        @if ($product->image)
                                                                        <img src="{{ asset($product->image) }}"
                                                                            id="photo" alt="Product Image"
                                                                            height="200">
                                                                        @else
                                                                        <img src="https://placehold.co/300x300?font=roboto"
                                                                            id="photo" alt="Product Image"
                                                                            class="shadow" height="300">
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 text-center">
                                                               <div class="d-flex">
                                                                    <div class="form-group col-md-6">
                                                                        <label class="mb-3 font-weight-bold">Name</label>
                                                                        <p>{{ $product->name }}</p>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class="mb-3 font-weight-bold">Quantity</label>
                                                                        <p>{{ $product->quantity }}</p>
                                                                    </div>
                                                               </div>
                                                               <div class="d-flex">
                                                                    <div class="form-group col-md-6">
                                                                        <label class="mb-3 font-weight-bold">Code</label>
                                                                        <p>{{ $product->code }}</p>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label class="mb-3 font-weight-bold">Category</label>
                                                                        <p>{{ $product->category->name }}</p>
                                                                    </div>
                                                               </div>
                                                               <div class="form-group col-md-12">
                                                                    <label class="mb-3 font-weight-bold">Description</label>
                                                                    <p>{{ $product->description }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
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
            $("#productTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#productTable_wrapper .col-md-6:eq(0)');
        });

        // Delete Product Confirmation alart
        function deleteProduct(button) {
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

                    let url = "{{ route('product.destroy', ':id') }}";
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
