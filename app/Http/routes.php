<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Login Page
Route::get('/', [
    'as' => 'login_path',
    'uses' => 'HomeController@index'
]);

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    //Dashboard
    Route::get('/dashboard', [
        'as' => 'dashboard_path',
        'uses' => 'HomeController@dashboard'
    ]);

    //Clients
    Route::get('/clients', [
        'as' => 'clients_path',
        'uses' => 'HomeController@clients'
    ]);

    //Client details
    Route::get('/client-{client_id}', [
        'as' => 'view_client_details_path',
        'uses' => 'HomeController@clientDetails'
    ]);

    //Client total amount due
    Route::get('/total-amount-due-{client_id}', [
        'as' => 'total_amount_due_path',
        'uses' => 'HomeController@totalAmountDue'
    ]);

    //Client total income
    Route::get('/total-income-{client_id}', [
        'as' => 'total_income_path',
        'uses' => 'HomeController@totalIncome'
    ]);

    //Client Next Payments
    Route::get('/next-payments-{client_id}', [
        'as' => 'next_payments_path',
        'uses' => 'HomeController@nextPayments'
    ]);

    //Timeline
    Route::get('/timeline-{client_id}', [
        'as' => 'client_timeline_path',
        'uses' => 'HomeController@clientTimeline'
    ]);

    //Add Client
    Route::get('/clients/add-client', [
        'as' => 'add_client_path',
        'uses' => 'HomeController@addClient'
    ]);

    //Add Client
    Route::post('/clients/add-client', [
        'as' => 'add_client_path',
        'uses' => 'HomeController@storeClient'
    ]);

    //Edit Client
    Route::get('/clients/edit-client-{client_id}', [
        'as' => 'edit_client_path',
        'uses' => 'HomeController@editClient'
    ]);

    //Edit Client
    Route::post('/clients/edit-client-{client_id}', [
        'as' => 'edit_client_path',
        'uses' => 'HomeController@updateClient'
    ]);

    //Hide Client
    Route::get('/clients/hide-client-{client_id}', [
        'as' => 'hide_client_path',
        'uses' => 'HomeController@hideClient'
    ]);

    //Hide Client
    Route::post('/clients/hide-client-{client_id}', [
        'as' => 'hide_client_path',
        'uses' => 'HomeController@hideClient'
    ]);




    //Invoices
    Route::get('/invoices', [
        'as' => 'invoices_path',
        'uses' => 'HomeController@invoices'
    ]);

    //Invoice Details
    Route::get('/invoice-{invoice_id}', [
        'as' => 'view_invoice_details_path',
        'uses' => 'HomeController@invoiceDetails'
    ]);

    //Add Invoice
    Route::get('/invoices/add-invoice', [
        'as' => 'add_invoice_path',
        'uses' => 'HomeController@addInvoice'
    ]);

    //Add Invoice
    Route::post('/invoices/add-invoice', [
        'as' => 'add_invoice_path',
        'uses' => 'HomeController@storeInvoice'
    ]);

    //Edit Invoice
    Route::get('/invoices/edit-invoice-{invoice_id}', [
        'as' => 'edit_invoice_path',
        'uses' => 'HomeController@editInvoice'
    ]);

    //Edit Invoice
    Route::post('/invoices/edit-invoice-{invoice_id}', [
        'as' => 'edit_invoice_path',
        'uses' => 'HomeController@updateInvoice'
    ]);

    //Print Invoice
    Route::get('/invoices/print-invoice-{invoice_id}', [
        'as' => 'print_invoice_path',
        'uses' => 'HomeController@printInvoice'
    ]);

    //Print Invoice
    Route::post('/invoices/print-invoice-{invoice_id}', [
        'as' => 'print_invoice_path',
        'uses' => 'HomeController@printInvoice'
    ]);

    //Hide Invoice
    Route::get('/invoices/hide-invoice-{invoice_id}', [
        'as' => 'hide_invoice_path',
        'uses' => 'HomeController@hideInvoice'
    ]);

    //Hide Invoice
    Route::post('/invoices/hide-invoice-{invoice_id}', [
        'as' => 'hide_invoice_path',
        'uses' => 'HomeController@hideInvoice'
    ]);




    //Transactions
    Route::get('/transactions', [
        'as' => 'transactions_path',
        'uses' => 'HomeController@transactions'
    ]);

    //Transaction Details
    Route::get('/transaction-{transaction_id}', [
        'as' => 'view_transaction_details_path',
        'uses' => 'HomeController@transactionDetails'
    ]);

    //Add Transaction
    Route::get('/transactions/add-transaction', [
        'as' => 'add_transaction_path',
        'uses' => 'HomeController@addTransaction'
    ]);

    //Add Transaction
    Route::post('/transactions/add-transaction', [
        'as' => 'add_transaction_path',
        'uses' => 'HomeController@storeTransaction'
    ]);

    //Edit Transaction
    Route::get('/transactions/edit-transaction-{transaction_id}', [
        'as' => 'edit_transaction_path',
        'uses' => 'HomeController@editTransaction'
    ]);

    //Edit Transaction
    Route::post('/transactions/edit-transaction-{transaction_id}', [
        'as' => 'edit_transaction_path',
        'uses' => 'HomeController@updateTransaction'
    ]);

    //Hide Transaction
    Route::get('/transactions/hide-transaction-{transaction_id}', [
        'as' => 'hide_transaction_path',
        'uses' => 'HomeController@hideTransaction'
    ]);

    //Hide Transaction
    Route::post('/transactions/hide-transaction-{transaction_id}', [
        'as' => 'hide_transaction_path',
        'uses' => 'HomeController@hideTransaction'
    ]);


});
