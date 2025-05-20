<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>
                    Setting Details
                </h3>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('quote.index') }}" class="btn btn-outline-info float-right">Back</a>
            </div>
            <div class="col-lg-12 d-flex justify-content-center">
                <div class="inline-page-menu my-2">
                    <ul class="list-unstyled m-0">
                        <li class="{{ Route::is('setting.index') ? 'active' : '' }}">
                            <a href="{{ route('setting.index') }}">General</a>
                        </li>
                        <li class="{{ Route::is('setting.currencies') ? 'active' : '' }}">
                            <a href="{{ route('setting.currencies') }}">Currencies</a>
                        </li>
                        <li class="{{ Route::is('setting.currencies') ? 'active' : '' }}">
                            <a href="{{ route('setting.currencies') }}">Invoices</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
