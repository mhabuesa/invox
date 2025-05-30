<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>
                    Settings
                </h3>
            </div>
            <div class="col-lg-12 d-flex justify-content-center">
                <div class="inline-page-menu">
                    <ul class="list-unstyled m-0">
                        <li class="{{ Route::is('setting.index') ? 'active' : '' }}">
                            <a href="{{ route('setting.index') }}">General</a>
                        </li>
                        <li class="{{ Route::is('setting.currencies') ? 'active' : '' }}">
                            <a href="{{ route('setting.currencies') }}">Currencies</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
