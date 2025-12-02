<!-- PHP Variables for Active Menus -->
@php
    $route = request()->route()->getName();
    $isIndex = request()->routeIs('index');
    $isQuote = request()->routeIs('quote.*');
    $isInvoice = request()->routeIs('invoice.*');
    $isTax = request()->routeIs('tax.*');
    $isProduct = request()->routeIs('product.*') || request()->routeIs('category.*');
    $isClient = request()->routeIs('client.*');
    $isUser = request()->routeIs('user.*');
    $isSetting = request()->routeIs('setting.*');
    $isDbBackup = request()->routeIs('dbBackup');
    $isRole = request()->routeIs('role.*');
    $activityLog = request()->routeIs('activityLog');
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('index') }}" class="brand-link">
        <img src="{{ asset($setting->logo ?? '/assets/dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image"
                style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $setting->app_name ?? config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- SidebarSearch Form -->
        <div class="form-inline mt-2">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('index') }}" class="nav-link {{ $isIndex ? 'active-single-nav' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @can('quote_access')
                    <!-- Quotes -->
                    <li class="nav-item">
                        <a href="{{ route('quote.index') }}" class="nav-link {{ $isQuote ? 'active-single-nav' : '' }}">
                            <i class="nav-icon fas fa-thumbtack fa-lg"></i>
                            <p>Quotes</p>
                        </a>
                    </li>
                @endcan

                @can('invoice_access')
                    <!-- Invoices -->
                    <li class="nav-item">
                        <a href="{{ route('invoice.index') }}" class="nav-link {{ $isInvoice ? 'active-single-nav' : '' }}">
                            <i class="nav-icon fas fa-file-invoice fa-lg"></i>
                            <p>Invoices</p>
                        </a>
                    </li>
                @endcan

                @can('tax_access')
                    <!-- Taxes -->
                    <li class="nav-item">
                        <a href="{{ route('tax.index') }}" class="nav-link {{ $isTax ? 'active-single-nav' : '' }}">
                            <i class="nav-icon fas fa-percent fa-lg"></i>
                            <p>Taxes</p>
                        </a>
                    </li>
                @endcan

                @if (Auth::user()->can('product_list') || Auth::user()->can('product_add'))
                    <!-- Product Management -->
                    <li class="nav-header">Products Management</li>
                    <li class="nav-item {{ $isProduct ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isProduct ? 'active open' : '' }}">
                            <i class="nav-icon fa fa-puzzle-piece"></i>
                            <p>
                                Products
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('product_list')
                                <li class="nav-item">
                                    <a href="{{ route('product.index') }}"
                                        class="nav-link {{ in_array($route, ['product.index', 'product.edit']) ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Product List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('product_add')
                                <li class="nav-item">
                                    <a href="{{ route('product.create') }}"
                                        class="nav-link {{ $route == 'product.create' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>New Product</p>
                                    </a>
                                </li>
                            @endcan

                            @can('category_access')
                                <li class="nav-item">
                                    <a href="{{ route('category.index') }}"
                                        class="nav-link {{ $route == 'category.index' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Category</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->can('client_list') || Auth::user()->can('client_add'))

                    <!-- Client Management -->
                    <li class="nav-header">Client Management</li>
                    <li class="nav-item {{ $isClient ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isClient ? 'active open' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Clients
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('client_list')
                                <li class="nav-item">
                                    <a href="{{ route('client.index') }}"
                                        class="nav-link {{ in_array($route, ['client.index', 'client.edit']) ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Client List</p>
                                    </a>
                                </li>
                            @endcan
                            @can('client_add')
                                <li class="nav-item">
                                    <a href="{{ route('client.create') }}"
                                        class="nav-link {{ $route == 'client.create' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>New Client</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->can('user_list') || Auth::user()->can('user_add'))
                    <!-- User Management -->
                    <li class="nav-header">User Management</li>
                    <li class="nav-item {{ $isUser ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isUser ? 'active open' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Users
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('user_list')
                                <li class="nav-item">
                                    <a href="{{ route('user.index') }}"
                                        class="nav-link {{ in_array($route, ['user.index', 'user.edit']) ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>User List</p>
                                    </a>
                                </li>
                            @endcan

                            @can('user_add')
                                <li class="nav-item">
                                    <a href="{{ route('user.create') }}"
                                        class="nav-link {{ $route == 'user.create' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>New User</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @can('setting')
                    <!-- Settings -->
                    <li class="nav-item">
                        <a href="{{ route('setting.index') }}"
                            class="nav-link {{ $isSetting ? 'active-single-nav' : '' }}">
                            <i class="nav-icon fas fa-cog fa-lg"></i>
                            <p>Setting</p>
                        </a>
                    </li>
                @endcan

                @can('role_management')
                    <!-- Role Management -->
                    <li class="nav-item">
                        <a href="{{ route('role.index') }}" class="nav-link {{ $isRole ? 'active-single-nav' : '' }}">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>Role Management</p>
                        </a>
                    </li>
                @endcan

                @can('activity_log')
                    <!-- Activity Log -->
                    <li class="nav-item">
                        <a href="{{ route('activityLog') }}" class="nav-link {{ $activityLog ? 'active-single-nav' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Activity Log</p>
                        </a>
                    </li>
                @endcan

                @can('db_backup')
                    <!-- DB Backup -->
                    <li class="nav-item">
                        <a href="{{ route('dbBackup') }}" class="nav-link {{ $isDbBackup ? 'active-single-nav' : '' }}">
                            <i class="nav-icon fas fa-database"></i>
                            <p>DB Backup</p>
                        </a>
                    </li>
                @endcan

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
