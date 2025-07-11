@section('title', 'Roles')
@extends('layouts.app')
@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">User Role List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="userTable" class="table table-bordered table-striped userTable">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>
                                                @forelse ($user->getRoleNames() as $key => $role)
                                                    <span class="badge badge-info">{{ formatPermission($role) }}</span>
                                                @empty
                                                    <span class="badge badge-danger">Not Assigned</span>
                                                @endforelse
                                            </td>
                                            <td>
                                                @if ($user->getRoleNames()->count() > 0 && !$user->hasRole('super_admin'))
                                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm"
                                                        onclick="deleteUserRole(this)" data-id="{{ $user->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Role List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="userTable" class="table table-bordered table-striped userTable">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Role</th>
                                        <th>Permissions</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ formatPermission($role->name) }}</td>
                                            <td>
                                                @if ($role->name == 'super_admin')
                                                    <span class="badge badge-success">No Permission Required</span>
                                                @elseif ($role->permissions->count() > 0)
                                                    @foreach ($role->permissions as $permission)
                                                        <span
                                                            class="badge badge-info">{{ formatPermission($permission->name) }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge badge-danger">No Permission</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($role->name != 'super_admin')
                                                    <a href="{{ route('role.edit', $role->id) }}"
                                                        class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm"
                                                        onclick="deleteRole(this)" data-id="{{ $role->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                @endif

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
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Create Role</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form action="{{ route('role.create') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="role_name">Role Name</label>
                                            <input type="text" class="form-control" id="role_name" name="role_name"
                                                placeholder="Enter role name" required value="{{ old('role_name') }}">
                                            @error('role_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="role">Select Permissions</label>
                                            <!-- checkbox -->
                                            <div class="form-group clearfix row">
                                                <div class="icheck-primary col-12 text-center my-2">
                                                    <input type="checkbox" id="select_all">
                                                    <label for="select_all">
                                                        Select All
                                                    </label>
                                                </div>
                                                @foreach ($permissions as $permission)
                                                    <div class="icheck-primary d-inline me-1 col-6">
                                                        <input type="checkbox" id="permission_{{ $permission->id }}"
                                                            name="permission[]" value="{{ $permission->id }}">
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
                                        <button type="submit" class="btn btn-info">Submit</button>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Role Assign To User</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form action="{{ route('role.assign') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="user">User</label>
                                            <select name="user_id" id="user" class="form-control" required>
                                                <option value="">Select User</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('user')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="role_id">Role</label>
                                            <select name="role" class="form-control" required>
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-info">Submit</button>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        function deleteUserRole(button) {
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
                    // Redirect to delete route
                    let url = "{{ route('user.role.delete', ':id') }}";
                    url = url.replace(':id', id);
                    window.location.href = url;
                }
            });
        }

        function deleteRole(button) {
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
                    // Redirect to delete route
                    let url = "{{ route('role.delete', ':id') }}";
                    url = url.replace(':id', id);
                    window.location.href = url;
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            // Select all toggle
            $('#select_all').on('change', function() {
                $('input[name="permission[]"]').prop('checked', this.checked);
            });

            // Individual uncheck -> uncheck select all
            $('input[name="permission[]"]').on('change', function() {
                if (!this.checked) {
                    $('#select_all').prop('checked', false);
                } else {
                    // If all are checked, then mark select_all as checked
                    if ($('input[name="permission[]"]:checked').length === $('input[name="permission[]"]')
                        .length) {
                        $('#select_all').prop('checked', true);
                    }
                }
            });
        });
    </script>
@endpush
