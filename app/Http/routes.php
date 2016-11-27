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

   
    /*---------------------------------------------------------
    CLIENTS SECTION
    -------------------------------------------------------------*/
    //Clients
    Route::get('/clients', [
        'as' => 'clients_path',
        'uses' => 'ClientController@index'
    ]);

    //Client details
    Route::get('/client-{client_id}', [
        'as' => 'view_client_details_path',
        'uses' => 'ClientController@show'
    ]);

    //Add Client
    Route::get('/clients/add-client', [
        'as' => 'add_client_path',
        'uses' => 'ClientController@add'
    ]);

    //Add Client
    Route::post('/clients/add-client', [
        'as' => 'add_client_path',
        'uses' => 'ClientController@store'
    ]);

    //Edit Client
    Route::get('/clients/edit-client-{client_id}', [
        'as' => 'edit_client_path',
        'uses' => 'ClientController@edit'
    ]);

    //Edit Client
    Route::post('/clients/edit-client-{client_id}', [
        'as' => 'edit_client_path',
        'uses' => 'ClientController@update'
    ]);

    //Hide Client
    Route::get('/clients/hide-client-{client_id}', [
        'as' => 'hide_client_path',
        'uses' => 'ClientController@hide'
    ]);


    /*---------------------------------------------------------
    SUPPLIER SECTION
    -------------------------------------------------------------*/

    //Suppliers
    Route::get('/suppliers', [
        'as' => 'suppliers_path',
        'uses' => 'SupplierController@index'
    ]);

    //Supplier details
    Route::get('/supplier-{supplier_id}', [
        'as' => 'view_supplier_details_path',
        'uses' => 'SupplierController@show'
    ]);

    //Add supplier
    Route::get('/suppliers/add-supplier', [
        'as' => 'add_supplier_path',
        'uses' => 'SupplierController@add'
    ]);

    //Add supplier
    Route::post('/suppliers/add-supplier', [
        'as' => 'add_supplier_path',
        'uses' => 'SupplierController@store'
    ]);

    //Edit supplier
    Route::get('/suppliers/edit-supplier-{supplier_id}', [
        'as' => 'edit_supplier_path',
        'uses' => 'SupplierController@edit'
    ]);

    //Edit supplier
    Route::post('/suppliers/edit-supplier-{supplier_id}', [
        'as' => 'edit_supplier_path',
        'uses' => 'SupplierController@update'
    ]);

    //Hide supplier
    Route::get('/suppliers/hide-supplier-{supplier_id}', [
        'as' => 'hide_supplier_path',
        'uses' => 'SupplierController@hide'
    ]);



    /*---------------------------------------------------------
    INVOICES SECTION
    -------------------------------------------------------------*/

    //Invoices
    Route::get('/invoices', [
        'as' => 'invoices_path',
        'uses' => 'InvoiceController@index'
    ]);

    //Invoice Details
    Route::get('/invoice-{invoice_id}', [
        'as' => 'view_invoice_details_path',
        'uses' => 'InvoiceController@show'
    ]);

    //Add Invoice
    Route::get('/invoices/add-invoice', [
        'as' => 'add_invoice_path',
        'uses' => 'InvoiceController@add'
    ]);

    //Add Invoice
    Route::post('/invoices/add-invoice', [
        'as' => 'add_invoice_path',
        'uses' => 'InvoiceController@store'
    ]);

    //Edit Invoice
    Route::get('/invoices/edit-invoice-{invoice_id}', [
        'as' => 'edit_invoice_path',
        'uses' => 'InvoiceController@edit'
    ]);

    //Edit Invoice
    Route::post('/invoices/edit-invoice-{invoice_id}', [
        'as' => 'edit_invoice_path',
        'uses' => 'InvoiceController@updateDraft'
    ]);

    //Edit no draft Invoice
    Route::post('/invoices/edit-no-draft-invoice-{invoice_id}', [
        'as' => 'edit_no_draft_invoice_path',
        'uses' => 'InvoiceController@updateNoDraft'
    ]);
    

    //Print Invoice
    Route::get('/invoices/print-invoice-{invoice_id}', [
        'as' => 'print_invoice_path',
        'uses' => 'InvoiceController@printt'
    ]);

    //Download Invoice
    Route::get('/invoices/download-invoice-{invoice_id}', [
        'as' => 'download_invoice_path',
        'uses' => 'InvoiceController@download'
    ]);

    //Send Invoice
    Route::get('/invoices/send-invoice-{invoice_id}', [
        'as' => 'send_invoice_path',
        'uses' => 'InvoiceController@send'
    ]);

    //Hide Invoice
    Route::get('/invoices/hide-invoice-{invoice_id}', [
        'as' => 'hide_invoice_path',
        'uses' => 'InvoiceController@hide'
    ]);


    /*---------------------------------------------------------
    TRANSACTION SECTION
    -------------------------------------------------------------*/


    //Transactions
    Route::get('/transactions', [
        'as' => 'transactions_path',
        'uses' => 'TransactionController@index'
    ]);

    //Transaction Details
    Route::get('/transaction-{transaction_id}', [
        'as' => 'view_transaction_details_path',
        'uses' => 'TransactionController@show'
    ]);

    //Add Transaction
    Route::get('/transactions/add-transaction', [
        'as' => 'add_transaction_path',
        'uses' => 'TransactionController@add'
    ]);

    //Add Transaction
    Route::post('/transactions/add-transaction', [
        'as' => 'add_transaction_path',
        'uses' => 'TransactionController@store'
    ]);

    //Hide Transaction
    Route::get('/transactions/hide-transaction-{transaction_id}', [
        'as' => 'hide_transaction_path',
        'uses' => 'TransactionController@hide'
    ]);



      Route::group(['middleware' => 'admin'], function () {

            /*---------------------------------------------------------
            DASHBOARD SECTION
            -------------------------------------------------------------*/

            //Dashboard
            Route::get('/dashboard', [
                'as' => 'dashboard_path',
                'uses' => 'DashboardController@index'
            ]);

            /*---------------------------------------------------------
            SALARIES SECTION
            -------------------------------------------------------------*/

            //generate salaries
            Route::get('/salary-{user_id}', [
                'as' => 'salary_path',
                'uses' => 'SalaryController@generate'
            ]);


            //preview salaries
            Route::post('/preview-salary-{user_id}-{month}-{year}', [
                'as' => 'preview_salary_path',
                'uses' => 'SalaryController@preview'
            ]);


            //store the salaries in the transaction
            Route::post('/add-salary-{user_id}', [
                'as' => 'add_salary_path',
                'uses' => 'SalaryController@store'
            ]);


            //Print salaries
            Route::get('/team/print-salary-{user_id}-{month}-{year}', [
                'as' => 'print_salary_path',
                'uses' => 'SalaryController@printt'
            ]);

            /*---------------------------------------------------------
            TRANSPORTATION SECTION
            -------------------------------------------------------------*/


            //view and add transportation
            Route::get('/transportation-{user_id}', [
                'as' => 'transportation_path',
                'uses' => 'TransportationController@add'
            ]);


            //store a transportation 
            Route::post('/add-transportation-{user_id}', [
                'as' => 'add_transportation_path',
                'uses' => 'TransportationController@store'
            ]);


            /*---------------------------------------------------------
            TEAM SECTION
            -------------------------------------------------------------*/

            //view profile for salaries
            Route::get('/team', [
                'as' => 'team_path',
                'uses' => 'TeamController@index'
            ]);

            //view profile details for salary
            Route::get('/profile-details-{user_id}', [
                'as' => 'profile_details_path',
                'uses' => 'TeamController@show'
            ]);


    });


});
