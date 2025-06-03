<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// Auth Middleware Group
Route::middleware('auth')->group(function () {

    //Dashboard Controller Group
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'home');
        Route::get('/dashboard', 'dashboard')->name('dashboard');
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




    //Resource Controller Extra Route
    Route::controller(ProductController::class)->name('product.')->prefix('product')->group(function () {
        Route::post('/addCategoryAjax', 'addCategoryAjax')->name('addCategoryAjax');
    });
    Route::controller(QuoteController::class)->name('quote.')->prefix('quote')->group(function () {
        Route::post('/addClientAjax', 'addClientAjax')->name('addClientAjax');
    });
    Route::controller(InvoiceController::class)->name('invoice.')->prefix('invoice')->group(function () {
        Route::post('/addClientAjax', 'addClientAjax')->name('addClientAjax');
        // Route::get('/', 'addClientAjax')->name('addClientAjax');
    });
    Route::controller(TaxController::class)->name('tax.')->prefix('tax')->group(function () {
        Route::post('/status/update/{id}', 'status_update')->name('status.update');
    });


    // Resource Controller
    Route::resource('/user', UserController::class);
    Route::resource('/client', ClientController::class);
    Route::resource('/category', CategoryController::class);
    Route::resource('/product', ProductController::class);
    Route::resource('/quote', QuoteController::class);
    Route::resource('/tax', TaxController::class);
    Route::resource('/invoice', InvoiceController::class);

});

require __DIR__ . '/auth.php';
