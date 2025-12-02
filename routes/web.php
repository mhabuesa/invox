<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// Auth Middleware Group
Route::middleware('auth','demo.restrict')->group(function () {

    //Dashboard Controller Group
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Common Controller Group
    Route::controller(CommonController::class)->group(function () {
        Route::get('/dbBackup', 'dbBackup')->name('dbBackup');
        Route::get('/activityLog', 'activityLog')->name('activityLog');
        Route::get('/activityLog/delete/{id}', 'activityLog_delete')->name('activityLog.delete');
    });

    // Profile Controller Group
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile', 'update')->name('profile.update');
        Route::post('/profile/password', 'password')->name('profile.password');
    });
    // Setting Controller Group
    Route::controller(SettingController::class)->group(function () {
        // General Setting
        Route::get('/setting', 'setting')->name('setting.index');
        Route::post('/setting/update', 'setting_update')->name('setting.update');
        Route::get('/setting/reload', 'setting_reload')->name('setting.reload');

        // Currencies
        Route::get('/setting/currencies', 'currencies')->name('setting.currencies');
        Route::post('/currency/store', 'currency_store')->name('currency.store');
        Route::post('/currency/update/{currency}', 'currency_update')->name('currency.update');
        Route::post('/currency/status/update/{id}', 'currency_status_update')->name('currency.status.update');
        Route::delete('/currency/destroy/{currency}', 'currency_destroy')->name('currency.destroy');

        //Invoice
        Route::get('/setting/invoice', 'invoice_setting')->name('setting.invoice');
        Route::post('/setting/invoice/update', 'invoice_setting_update')->name('setting.invoice.update');
        Route::delete('/remove/signature', 'remove_signature')->name('remove.signature');
    });

    // Role Management Controller
    Route::controller(RoleController::class)->group(function () {
        Route::get('/role', 'index')->name('role.index');
        Route::post('/permission/store', 'permission_store')->name('permission.store');
        Route::post('/create/role', 'create_role')->name('role.create');
        Route::get('/role/edit/{id}', 'role_edit')->name('role.edit');
        Route::post('/role/update/{id}', 'role_update')->name('role.update');
        Route::get('/role/delete/{id}', 'role_delete')->name('role.delete');
        Route::post('/role/assign', 'role_assign')->name('role.assign');
        Route::get('/user/role/delete/{id}', 'user_role_delete')->name('user.role.delete');
    });




    //Resource Controller Extra Route
    Route::controller(ProductController::class)->name('product.')->prefix('product')->group(function () {
        Route::post('/addCategoryAjax', 'addCategoryAjax')->name('addCategoryAjax');
    });
    Route::controller(QuoteController::class)->name('quote.')->prefix('quote')->group(function () {
        Route::post('/addClientAjax', 'addClientAjax')->name('addClientAjax');
        Route::get('/convertToInvoice/{id}', 'convertToInvoice')->name('convertToInvoice');
    });
    Route::controller(InvoiceController::class)->name('invoice.')->prefix('invoice')->group(function () {
        Route::post('/addClientAjax', 'addClientAjax')->name('addClientAjax');
        Route::get('/payment/{id}', 'payment')->name('payment');
        Route::post('/payment/store/{id}', 'payment_store')->name('payment.store');

    });
    Route::controller(TaxController::class)->name('tax.')->prefix('tax')->group(function () {
        Route::post('/status/update/{id}', 'status_update')->name('status.update');
    });


    // Resource Controller
    Route::resource('/quote', QuoteController::class);
    Route::resource('/invoice', InvoiceController::class);
    Route::resource('/tax', TaxController::class);
    Route::resource('/product', ProductController::class);
    Route::resource('/category', CategoryController::class);
    Route::resource('/client', ClientController::class);
    Route::resource('/user', UserController::class);
});

// Installation Middleware Group
Route::controller(InstallationController::class)->group(function () {
    Route::get('/install', 'install')->name('install');
    Route::post('/verify-license', 'verifyLicense');
    Route::post('/check-db', 'check_db')->name('check.db');
    Route::post('/admin/setup', 'admin_setup')->name('admin.setup');
});

require __DIR__ . '/auth.php';
