@extends('layouts.app')
@section('title', 'Add Client')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-4 m-auto">
                    <!-- general form elements -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title my-1">Add New Client</h3>
                            <a href="{{ route('client.index') }}" class="btn btn-dark btn-sm float-right"> <i
                                    class="fas fa-arrow-left"></i> &nbsp; Back to List</a>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('client.store') }}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter your name"
                                        name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter your email"
                                        name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number <small class="text-muted">(Optional)</small></label>
                                    <input type="phone" class="form-control" id="phone" placeholder="Enter your Phone Number"
                                        name="phone" value="{{ old('phone') }}">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address <small class="text-muted">(Optional)</small></label>
                                    <input type="address" class="form-control" id="address" placeholder=" Enter your Address (Optional)"
                                        name="address" value="{{ old('address') }}">
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
