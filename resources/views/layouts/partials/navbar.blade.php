<nav class="main-header navbar navbar-expand nav_dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="">
            <span class=" nav_hover">@yield('title')</span>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- User Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                @if (Auth::user()->image)
                    <img src="{{ asset(Auth::user()->image) }}" alt="User Avatar"
                        class="img-circle mr-2" width="30">
                @else
                    <img src="https://placehold.co/100x100?font=roboto" alt="User Avatar" class="img-circle mr-2"
                        width="30">
                @endif
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-sm p-0 dropdown-menu-right userNav">

                <div class="p-3 text-center bg-gray-light border-bottom rounded-top">
                    @if (Auth::user()->image)
                        <img class="img-avatar img-avatar48 img-avatar-thumb img-circle"
                            src="{{ asset(Auth::user()->image) }}" width="80" alt="">
                    @else
                        <img class="img-avatar img-avatar48 img-avatar-thumb img-circle"
                            src="https://placehold.co/100x100?font=roboto" width="80" alt="">
                    @endif

                    <p class="mt-2 mb-0 fw-medium">{{ Auth::user()->name }}</p>
                </div>
                <div class="p-2">

                    <!-- Profile Button -->
                    <a class="dropdown-item pl-0 p-2 mb-2 d-flex align-items-center" href="{{ route('profile.index') }}">
                        <i class="fas fa-fw fa-user"></i> <span class="fs-sm fw-medium mx-2">Profile</span>
                    </a>
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item p-0 p-2 d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            <i class="fas fa-fw fa-sign-out-alt"></i> <span class="fs-sm fw-medium mx-2">Log
                                Out</span>
                        </a>
                    </form>

                </div>
            </div>
        </li>
    </ul>
</nav>
