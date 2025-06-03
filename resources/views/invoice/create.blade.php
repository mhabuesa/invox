@extends('layouts.app')
@section('title', 'Create Invoice')
@push('header')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>
                        Create New Invoice
                    </h3>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('invoice.index') }}" class="btn btn-outline-info float-right">Back</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12 m-auto">
                    <!-- general form elements -->
                    <!-- form start -->
                    <form method="POST" action="{{ route('invoice.store') }}">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title my-1">Basic Details</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="client">Client <span class="text-danger">*</span></label>
                                        <div class="input-group mb-3">
                                            <select class="form-control select2" name="client_id" id="client" required>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <div class="input-group-text" data-toggle="modal"
                                                    data-target="#clientModal">
                                                    <span class="fas fa-plus text-info"></span>
                                                </div>
                                            </div>
                                            @error('client_id')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <div class="form-group">
                                            <label for="invoice_number">Invoice # <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="invoice_number"
                                                placeholder="Enter Invoice Number" name="invoice_number"
                                                value="{{ old('invoice_number') ?? $invoice_number }}" required>
                                        </div>
                                        @error('invoice_number')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <div class="form-group">
                                            <label>Date: <span class="text-danger">*</span></label>
                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                <input type="date" class="form-control" required name="invoice_date" value="{{ date('Y-m-d') ?? old('invoice_date') }}">
                                            </div>
                                            @error('invoice_date')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title my-1">Product Details</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="rowContainer">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row d-flex justify-content-between">
                                                <div class="form-group col-md-3 col-12">
                                                    <label for="product_id">Product <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group mb-3">
                                                        <select class="form-control select2 product_id" name="product_id[]"
                                                            required>
                                                            <option value="">Select Product</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    data-unit_price="{{ $product->unit_price }}">
                                                                    {{ $product->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>Qty <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control qty" name="qty[]"
                                                        value="1" required>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>Unit Price <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control unit_price"
                                                        name="unit_price[]" value="0" required>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label for="tax">Tax</label>
                                                    <div class="input-group mb-3">
                                                        <select class="form-control tax" id="tax" name="tax_id[]">
                                                            <option value="0">Select Tax</option>
                                                            @foreach ($taxes as $tax)
                                                                <option {{ $tax->status == 1 ? 'selected' : '' }}
                                                                    value="{{ $tax->id }}" data-tax="{{ $tax->value }}">{{ $tax->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-1">
                                                    <label>Amount</label>
                                                    <strong class="d-block total">0.00</strong>
                                                </div>

                                                <div class="form-group col-md-1">
                                                    <div class="text-center">
                                                        <strong class="mt-3 delRow cursor-pointer d-none">
                                                            <i class="fas fa-times"></i>
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-outline-primary mt-3 btn-sm" id="addRow"><i
                                            class="fas fa-plus"></i> Add Product</button>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title my-1">Summary</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row d-flex justify-content-around">
                                    <div class="form-group col-12 col-md-6 col-lg-6">
                                        <div class="row">
                                            <div class="col-12 col-md-4 col-lg-4">
                                                <label for="discount_timing">Discount Apply Timing</label>
                                                <div class="input-group mb-3">
                                                    <select name="discount_timing" id="discount_timing"
                                                        class="form-control">
                                                        <option value="before_tax">Applied Before Tax</option>
                                                        <option value="after_tax">Applied After Tax</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4 col-lg-4">
                                                <label for="discount_type">Discount Type</label>
                                                <div class="input-group mb-3">
                                                    <select name="discount_type" id="discount_type" class="form-control">
                                                        <option value="flat">Flat</option>
                                                        <option value="percentage">Percentage</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4 col-lg-4">
                                                <label for="discount_type">Discount</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" id="discount"
                                                        name="discount" placeholder="Enter discount amount">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-lg-4">
                                        <div class="mb-3 d-flex justify-content-between">
                                            <strong>Sub Total :</strong>
                                            <strong id="subTotal">0.00</strong>
                                        </div>
                                        <div class="mb-3 d-flex justify-content-between">
                                            <strong>Discount :</strong>
                                            <strong id="discountTotal">0.00</strong>
                                        </div>
                                        <div class="mb-3 d-flex justify-content-between">
                                            <strong>Tax :</strong>
                                            <strong><span id="totalTax">0.00</span></strong>
                                        </div>
                                        <div class="mb-3 d-flex justify-content-between">
                                            <strong>Total :</strong>
                                            <strong><span id="grandTotal">0.00</span></strong>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title my-1">Note</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <textarea class="form-control" name="note" id="note" rows="3"
                                            placeholder="Write Some Note">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <button type="submit"
                            class="btn btn-info mt-5 w-25 d-flex mx-auto justify-content-center">Submit</button>
                    </form>
                </div>

            </div>
            <!-- /.row -->
        </div>
    </section>

    <!-- Modal for Add new client -->
    <div class="modal fade" id="clientModal" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Client</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="clientForm" method="POST" action="{{ route('invoice.addClientAjax') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="client_name"
                                placeholder="Enter Client Name">
                            <div class="text-danger mt-1 error-message" id="client_name_error"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" class="form-control" name="email" placeholder="Enter Client Email">
                            <div class="text-danger mt-1 error-message" id="email_error"></div>
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
@endpush
