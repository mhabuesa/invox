@extends('layouts.app')
@section('title', 'Profile')
@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if (Auth::user()->image)
                                    <img class="profile-user-img img-fluid img-circle" src="{{ asset(Auth::user()->image) }}"
                                        alt="User profile picture">
                                @else
                                    <img class="profile-user-img img-fluid img-circle"
                                        src="https://placehold.co/100x100?font=roboto" alt="User profile picture">
                                @endif
                            </div>

                            <h3 class="profile-username text-center mb-4">{{ Auth::user()->name }}</h3>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">{{ Auth::user()->email }}</a>
                                </li>
                                <li class="list-group-item border-0">
                                    <b>Account Created At</b> <a
                                        class="float-right">{{ Auth::user()->created_at->diffForHumans() }}</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        @php
                            $activeTab =
                                session('active_tab') ??
                                ($errors->has('current_password') ||
                                $errors->has('password') ||
                                $errors->has('password_confirmation')
                                    ? 'password'
                                    : 'settings');
                        @endphp
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'settings' ? 'active' : '' }}" href="#settings" data-toggle="tab">Settings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'password' ? 'active' : '' }}" href="#password" data-toggle="tab">Password</a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="tab-pane {{ $activeTab == 'settings' ? 'active' : '' }}" id="settings">
                                    <form class="form-horizontal p-4 " method="POST" action="{{ route('profile.update') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group text-center mb-4">
                                            <label class="mb-3 font-weight-bold">Profile Image</label>
                                            <div class="mb-3">
                                                @if (Auth::user()->image)
                                                    <img src="{{ asset(Auth::user()->image) }}" id="image"
                                                        alt="Profile Image" class="rounded-circle shadow border"
                                                        width="150">
                                                @else
                                                    <img src="https://placehold.co/100x100?font=roboto" id="image"
                                                        alt="Profile Image" class="rounded-circle shadow" width="150">
                                                @endif
                                            </div>
                                            <div class="custom-file w-25 mx-auto">
                                                <input type="file" class="custom-file-input" name="image"
                                                    id="inputImage"
                                                    onchange="document.getElementById('image').src = window.URL.createObjectURL(this.files[0])">
                                                <label class="custom-file-label d-flex" for="inputImage">Choose file</label>
                                            </div>
                                            @error('image')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName"
                                                class="col-sm-2 col-form-label font-weight-bold">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inputName" placeholder="Name"
                                                    name="name" value="{{ Auth::user()->name }}">
                                                @error('name')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mt-3">
                                            <label for="inputEmail"
                                                class="col-sm-2 col-form-label font-weight-bold">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="inputEmail"
                                                    placeholder="Email" name="email" value="{{ Auth::user()->email }}">
                                                @error('email')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mt-4">
                                            <div class="offset-sm-2 col-sm-10 text-right">
                                                <button type="submit" class="btn btn-primary px-4">Update Profile</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>

                                <div class="tab-pane {{ $activeTab == 'password' ? 'active' : '' }}" id="password">
                                    <form class="form-horizontal" method="POST" action="{{ route('profile.password') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="inputPassword" class="col-sm-2 col-form-label">Current
                                                Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="inputPassword"
                                                    placeholder="Current Password" name="current_password">
                                                @error('current_password')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="password" class="col-sm-2 col-form-label">New Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="password"
                                                    placeholder="Password" name="password">
                                                @error('password')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="confirmPassword" class="col-sm-2 col-form-label">Confirm
                                                Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="confirmPassword"
                                                    placeholder="Confirm Password" name="password_confirmation">
                                                @error('password_confirmation')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mt-4">
                                            <div class="offset-sm-2 col-sm-10 text-right">
                                                <button type="submit" class="btn btn-primary px-4">Update
                                                    Password</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection
