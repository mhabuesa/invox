@section('title', 'Role Edit')
@extends('layouts.app')
@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6 m-auto">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Role</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="{{ route('role.update', $role->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Role Name</label>
                                    <input type="text" name="role" id="name" class="form-control"
                                        value="{{ $role->name }}" required>
                                    @error('role')
                                        <span class="text-danger text-capitalize">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="role">Select Permissions</label>
                                    <!-- checkbox -->
                                    <div class="form-group clearfix row">
                                        @foreach ($permissions as $permission)
                                            <div class="icheck-primary col-6 col-4 ">
                                                <input type="checkbox" @checked($role->hasPermissionTo($permission->name)) id="permission_{{ $permission->id }}"
                                                    name="permissions[]" value="{{ $permission->name }}">
                                                <label for="permission_{{ $permission->id }}">
                                                    {{ formatPermission($permission->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('permission')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-info">Update</button>
                            </form>
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
