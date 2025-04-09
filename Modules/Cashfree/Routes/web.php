<?php

use Illuminate\Support\Facades\Route;
use Modules\Cashfree\Http\Controllers\CashfreeController;

Route::middleware(['setData', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu', 'CheckUserLogin'])
    ->group(function () {
        Route::prefix('cashfree')->group(function () {
            Route::get('/transactions', [CashfreeController::class, 'getPaymentTransactions'])->name('cashfree.transactions');
            Route::get('/payment-links', [CashfreeController::class, 'paymentLinks'])->name('cashfree.payment-links');
            Route::get('/api/transactions', 'CashfreeController@getPaymentTransactionsApi')->name('cashfree.api.transactions');
        });
    });