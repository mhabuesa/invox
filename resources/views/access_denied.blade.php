@extends('layouts.app')
@section('title', 'Access Denied')
@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mt-5">
                                <h1>🚫 Access Denied</h1>
                                <p>You do not have permission to access this page.</p>
                                <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Go Back</a>
                            </div>
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
