@extends('layouts.app')
@section('title', 'Edit Customer')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-4 m-auto">
                    <!-- general form elements -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title my-1">Edit Customer Info</h3>
                            <a href="{{ route('customer.index') }}" class="btn btn-dark btn-sm float-right"> <i
                                    class="fas fa-arrow-left"></i> &nbsp; Back to List</a>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('customer.update', $customer->id) }}">
                            @method('PUT')
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter your name"
                                        name="name" value="{{ $customer->name }}">
                                    @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter your email"
                                        name="email" value="{{ $customer->email }}">
                                    @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="phone" class="form-control" id="phone" placeholder="Enter your Phone Number"
                                        name="phone" value="{{ $customer->phone }}">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="address" class="form-control" id="address" placeholder=" Enter your Address (Optional)"
                                        name="address" value="{{ $customer->address }}">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">

                                <button type="submit" class="btn btn-info float-right">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>

            </div>
            <!-- /.row -->
        </div>
    </section>
@endsection
