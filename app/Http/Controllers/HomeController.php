<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\HomeRepository;
use Session;
use Mail;
use PDF;
use Carbon\Carbon;


class HomeController extends Controller
{
    /**
     * @var CmsRepository;
     */
    private $homeRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.login');
    }

    public function dashboard()
    {    
        // get the actual year 
        $actual_date = Carbon::now('Asia/Beirut');
        $actual_year = date('Y', strtotime($actual_date));


        $tab_month[1] = 'JAN';
        $tab_month[2] = 'FEB';
        $tab_month[3] = 'MAR';
        $tab_month[4] = 'APR';
        $tab_month[5] = 'MAY';
        $tab_month[6] = 'JUN';
        $tab_month[7] = 'JUL';
        $tab_month[8] = 'AUG';
        $tab_month[9] = 'SEP';
        $tab_month[10] = 'OCT';
        $tab_month[11] = 'NOV';
        $tab_month[12] = 'DEC';



        // ------------------ TOTAL INCOME ---------------

        $tab_income =  array();

        //declare all the income of the month to 0
        for($i=1; $i<=12; $i++)
        {
            $tab_income[$i] = 0;
        }

        // calculate the total income of every month
         $income = $this->homeRepository->getIncomes();

        // affect the amount of every month to the specific index
        foreach($income as $in)
        {
            $tab_income[$in->this_month] = $in->total_income;
        }



        // ------------------ TOTAL EXPENSES ---------------

         $tab_expenses =  array();

        //declare all the expenses of the month to 0
        for($j=1; $j<=12; $j++)
        {
            $tab_expenses[$j] = 0;
        }

        // calculate the total expenses of every month
         $expenses = $this->homeRepository->getExpenses();

        // affect the amount of every month to the specific index
        foreach($expenses as $e)
        {
            $tab_expenses[$e->this_month] = $e->total_expenses;
        }


      
        return view('dashboard', array('actual_year' => $actual_year, 'tab_month' => $tab_month, 'tab_income' => $tab_income, 'tab_expenses' => $tab_expenses));
    }


    /* --------------------------------
    CLIENTS
    -----------------------------------*/

    public function clients()
    {
        //gets the list of all clients
        $clientsList = $this->homeRepository->getClients();
        return view('clients.index', array('clientsList' => $clientsList));
    }

    public function clientDetails($client_id)
    {
        //gets all information of a specific client
        $clientInfo = $this->homeRepository->getSource($client_id);
        //gets all due invoices for a client
        $dueInvoicesList = $this->homeRepository->getDueInvoices($client_id);
        //gets all payed invoices for a client
        $payedInvoicesList = $this->homeRepository->getPayedInvoices($client_id);

        return view('clients.client-details', array('clientInfo' => $clientInfo, 'dueInvoicesList' => $dueInvoicesList, 'payedInvoicesList' => $payedInvoicesList));
    }

    public function totalAmountDue($client_id)
    {
        //gets all due invoices for a client
        $dueInvoicesList = $this->homeRepository->getDueInvoices($client_id);
        return view('clients.total-amount-due', array('dueInvoicesList' => $dueInvoicesList));
    }

    public function totalIncome($client_id)
    {
        //gets all payed invoices for a client
        $payedInvoicesList = $this->homeRepository->getPayedInvoices($client_id);
        return view('clients.total-income', array('payedInvoicesList' => $payedInvoicesList));
    }

    public function nextPayments($client_id)
    {
        //gets all due invoices for a client
        $dueInvoicesList = $this->homeRepository->getDueInvoices($client_id);
        return view('clients.next-payments', array('dueInvoicesList' => $dueInvoicesList));
    }

    public function clientTimeline($client_id)
    {
        //gets all information of a specific client
        $clientInfo = $this->homeRepository->getSource($client_id);
        return view('clients.timeline', array('clientInfo' => $clientInfo));
    }

    public function addClient()
    {  
        return view('clients.add-client');
    }

    public function storeClient(Request $request)
    {
        //if the submit button of the add client page is clicked, validate the input and insert them in the database
        $this->validate($request, ['client_name' => 'required', 
                                    'client_desc' => 'required', 
                                    'client_email' => 'required',
                                    'client_phone' => 'required',
                                    'client_address' => 'required',
                                    'client_owner' => 'required',
                                    'client_contact' => 'required']);

        if ($request->hasFile('client_img')) 
        {  
            $file = $request->file('client_img');
            $file_name = time() . '-' . $file->getClientOriginalName(); 
            $file->move('images/clients/', $file_name);
            $request->filename = $file_name;
        }


        $this->homeRepository->addClient($request->only('client_name', 'client_desc', 'client_email', 'client_phone', 'client_address', 'client_owner', 'client_contact', 'client_accounting'), $request->filename);
        $request->session()->flash('flash_message','Client Successfully added!');

        //gets all clients and their information 
        $clientsList = $this->homeRepository->getClients();
        return view('clients.index', array('clientsList' => $clientsList));
    }

    public function editClient($client_id)
    {
        //gets all information of a specific client
        $clientInfo = $this->homeRepository->getSource($client_id);
        return view('clients.edit-client', array('client_id' => $client_id, 'clientInfo' => $clientInfo));
    }

    public function updateClient(Request $request)
    {   
        //gets all information of a specific client
        $clientInfo = $this->homeRepository->getSource($request->input('source_id'));

        //if the submit button of the edit client page is clicked, validate the input and update them in the database
        $this->validate($request, ['source_id' => 'required',
                                    'source_name' => 'required', 
                                    'source_desc' => 'required', 
                                    'source_email' => 'required',
                                    'source_phone' => 'required',
                                    'source_address' => 'required',
                                    'source_owner' => 'required',
                                    'source_contact' => 'required']);

        if ($request->hasFile('client_img')) 
        {  
            $file = $request->file('client_img');
            $file_name = time() . '-' . $file->getClientOriginalName(); 
            $file->move('images/clients/', $file_name);
            $request->filename = $file_name;
        }

        else if($clientInfo[0]->img != NULL)
            $request->filename = $clientInfo[0]->img;

        $this->homeRepository->updateSource($request->only('source_id', 'source_name', 'source_desc', 'source_email', 'source_phone', 'source_address', 'source_owner', 'source_contact', 'source_accounting'), $request->filename);
        $request->session()->flash('flash_message','Client Successfully updated!');

        //gets all clients and their information 
        $clientsList = $this->homeRepository->getClients();
        return view('clients.index', array('clientsList' => $clientsList));
    }

    public function hideClient(Request $request, $client_id)
    {
        $this->homeRepository->hideSource($client_id);

        //gets all clients and their information 
        $clientsList = $this->homeRepository->getClients();
        $request->session()->flash('flash_message','Client Successfully removed!');

        return view('clients.index', array('clientsList' => $clientsList));
    }



    /* --------------------------------
    SUPPLIERS
    -----------------------------------*/

    public function suppliers()
    {
        //gets the list of all suppliers
        $suppliersList = $this->homeRepository->getSuppliers();
        return view('suppliers.index', array('suppliersList' => $suppliersList));
    }

    public function supplierDetails($supplier_id)
    {
        //gets all information of a specific supplier
        $supplierInfo = $this->homeRepository->getSource($supplier_id);
        //gets all spent invoices for a supplier
        $spentTransactionsList = $this->homeRepository->getSpentTransactions($supplier_id);
        return view('suppliers.supplier-details', array('supplierInfo' => $supplierInfo, 'spentTransactionsList' => $spentTransactionsList));
    }

    public function totalAmountSpent($supplier_id)
    {
        //gets all spent invoices for a supplier
        $spentTransactionsList = $this->homeRepository->getSpentTransactions($supplier_id);
        return view('suppliers.total-amount-spent', array('spentTransactionsList' => $spentTransactionsList));
    }

    public function supplierTimeline($supplier_id)
    {
        //gets all information of a specific supplier
        $supplierInfo = $this->homeRepository->getSource($supplier_id);
        return view('suppliers.timeline', array('supplierInfo' => $supplierInfo));
    }

    public function addSupplier()
    {
        return view('suppliers.add-supplier');
    }

    public function storeSupplier(Request $request)
    {
        //if the submit button of the add supplier page is clicked, validate the input and insert them in the database
        $this->validate($request, ['supplier_name' => 'required', 
                                    'supplier_desc' => 'required', 
                                    'supplier_email' => 'required',
                                    'supplier_phone' => 'required',
                                    'supplier_address' => 'required',
                                    'supplier_owner' => 'required',
                                    'supplier_contact' => 'required']);
       
        if ($request->hasFile('supplier_img')) 
        {  
            $file = $request->file('supplier_img');
            $file_name = time() . '-' . $file->getClientOriginalName(); 
            $file->move('images/suppliers/', $file_name);
            $request->filename = $file_name;
        }


        $this->homeRepository->addSupplier($request->only('supplier_name', 'supplier_desc', 'supplier_email', 'supplier_phone', 'supplier_address', 'supplier_owner', 'supplier_contact', 'supplier_accounting'), $request->filename);
        $request->session()->flash('flash_message','Supplier Successfully added!');

        //gets all suppliers and their information 
        $suppliersList = $this->homeRepository->getSuppliers();
        return view('suppliers.index', array('suppliersList' => $suppliersList));
    }

    public function editSupplier($supplier_id)
    {
        //gets all information of a specific supplier
        $supplierInfo = $this->homeRepository->getSource($supplier_id);
        return view('suppliers.edit-supplier', array('supplier_id' => $supplier_id, 'supplierInfo' => $supplierInfo));
    }

    public function updateSupplier(Request $request)
    {
        //if the submit button of the edit supplier page is clicked, validate the input and update them in the database
        $this->validate($request, ['source_id' => 'required',
                                    'source_name' => 'required', 
                                    'source_desc' => 'required', 
                                    'source_email' => 'required',
                                    'source_phone' => 'required',
                                    'source_address' => 'required',
                                    'source_owner' => 'required',
                                    'source_contact' => 'required']);

        if ($request->hasFile('supplier_img')) 
        {  
            $file = $request->file('supplier_img');
            $file_name = time() . '-' . $file->getClientOriginalName(); 
            $file->move('images/suppliers/', $file_name);
            $request->filename = $file_name;
        }

        $this->homeRepository->updateSource($request->only('source_id', 'source_name', 'source_desc', 'source_email', 'source_phone', 'source_address', 'source_owner', 'source_contact', 'source_accounting'), $request->filename);
        $request->session()->flash('flash_message','Supplier Successfully updated!');

        //gets all suppliers and their information 
        $suppliersList = $this->homeRepository->getSuppliers();
        return view('suppliers.index', array('suppliersList' => $suppliersList));
    }

    public function hideSupplier(Request $request, $supplier_id)
    {
        $this->homeRepository->hideSource($supplier_id);

        //gets all suppliers and their information 
        $suppliersList = $this->homeRepository->getSuppliers();
        $request->session()->flash('flash_message','Supplier Successfully removed!');

        return view('suppliers.index', array('suppliersList' => $suppliersList));
    }



    /* --------------------------------
    INVOICES
    -----------------------------------*/

    public function invoices()
    {
        //gets all invoices and their information 
        $invoicesList = $this->homeRepository->getInvoices();
        return view('invoices.index', array('invoicesList' => $invoicesList));
    }

    public function invoiceDetails($invoice_id)
    {
        //get invoice items
        $invoiceItems = $this->homeRepository->getInvoiceItems($invoice_id);
        //gets all information of a specific invoice
        $invoiceInfo = $this->homeRepository->getInvoice($invoice_id);
        $data = array_merge($invoiceInfo, $invoiceItems);

        return view('invoices.invoice-details', array('data' => $data));
    }

    public function addInvoice()
    {
        //gets all clients 
        $clients = $this->homeRepository->getClientNames();
        //gets all services
        $services = $this->homeRepository->getServices();
        return view('invoices.add-invoice', array('clients' => $clients, 'services' => $services));
    }

    public function storeInvoice(Request $request)
    {
        //Initialization of amount of the invoice
        $amount = 0;

        //Parsing all the items of the invoice that we need to add
        for($i=1;$i<=($request->input('item_number'));$i++)
        {
            $amount = $amount + $request->input('invoice_item_amount_'.$i);
        }

        //if the submit button of the add invoice page is clicked, validate the input and insert them in the database
        $this->validate($request, ['invoice_client' => 'required',
                                    'invoice_date' => 'required']);
        $this->homeRepository->addInvoice($request->only('invoice_client', 'invoice_date', 'invoice_next_payment'), $amount);

        $lastInvoiceId = $this->homeRepository->getLastInvoiceId();

        for($i=1;$i<=($request->input('item_number'));$i++)
        {
            //if the submit button of the add invoice page is clicked, validate the input and insert them in the database
            $this->validate($request, ['invoice_item_service_'.$i => 'required', 
                                        'invoice_item_description_'.$i => 'required', 
                                        'invoice_item_amount_'.$i => 'required']);
            $this->homeRepository->addInvoiceItems($lastInvoiceId[0]->invoice_id, $request->input('invoice_item_service_'.$i), $request->input('invoice_item_description_'.$i), $request->input('invoice_item_amount_'.$i));
        }

        $request->session()->flash('flash_message','Invoice Successfully added!');

        //gets all invoices and their information 
        $invoicesList = $this->homeRepository->getInvoices();

        return view('invoices.index', array('invoicesList' => $invoicesList));
    }

    public function editInvoice($invoice_id)
    {   
        //gets all clients 
        $clients = $this->homeRepository->getClientNames();

        //gets all statuses
        $status = $this->homeRepository->getAllStatus();

        //get invoice items
        $invoiceItems = $this->homeRepository->getInvoiceItems($invoice_id);

        //gets all information of a specific invoice
        $invoiceInfo = $this->homeRepository->getInvoice($invoice_id);

        //gets all the paid transactions for a specific invoice id
        $invoiceTransactions = $this->homeRepository->getTransactionFromInvoiceId($invoice_id);

        return view('invoices.edit-invoice', array('invoice_id' => $invoice_id, 'clients' => $clients, 'status' => $status, 'invoiceItems' => $invoiceItems, 
                                                   'invoiceInfo' => $invoiceInfo, 'invoiceTransactions' => $invoiceTransactions));
    }

    public function updateInvoice(Request $request)
    {   

        //Initialization of amount of the invoice
        $amount = 0;
        //Parsing all the items of the invoice that we need to update
        for($i=1;$i<=($request->input('item_number')-1);$i++)
        {
            //if the submit button of the add invoice page is clicked, validate the input and insert them in the database
            $this->validate($request, ['invoice_item_id_'.$i => 'required',
                                        'invoice_item_description_'.$i => 'required', 
                                        'invoice_item_amount_'.$i => 'required']);
            //Update all the items of the invoice
            $this->homeRepository->updateInvoiceItems($request->input('invoice_item_id_'.$i), $request->input('invoice_item_description_'.$i), $request->input('invoice_item_amount_'.$i));
            //Store the total amount of the invoice
            $amount = $amount + $request->input('invoice_item_amount_'.$i);
        }

        //if the submit button of the edit invoice page is clicked, validate the input and update them in the database
        $this->validate($request, ['invoice_id' => 'required', 
                                    'invoice_date' => 'required',
                                    'invoice_status' => 'required']);

        //Update the invoice with the final amount calculated above
        $this->homeRepository->updateInvoice($request->only('invoice_id', 'invoice_client', 'invoice_date', 'invoice_status', 'invoice_paid'), $amount);
        $request->session()->flash('flash_message','Invoice Successfully updated!');

        //gets all invoices and their information 
        $invoicesList = $this->homeRepository->getInvoices();


        return view('invoices.index', array('invoicesList' => $invoicesList));
    }



    public function updateNoDraftInvoice(Request $request)
    {   
        
        //gets all information of a specific invoice
        $invoiceInfo = $this->homeRepository->getInvoice($request->input('invoice_id'));

        // if we need to enter a new payment
        if($request->input('new_payment') == 1)
        {
            $new_payment_value = $request->input('invoice_paid');
            $this->validate($request, ['invoice_paid' => 'required']);
            
            //concatenate the description in a variable
            $description = $new_payment_value.' USD payed from the invoice number '.$invoiceInfo[0]->invoice_id;
            $actual_date = date('Y-m-d H:i:s');
            //add a transaction for the payment of the invoice
            $this->homeRepository->addTransaction($invoiceInfo[0]->invoice_id, null, $invoiceInfo[0]->client_id, $new_payment_value, $description, 10, 
                                                  $actual_date, 1);

            if($invoiceInfo[0]->paid != null && $invoiceInfo[0]->paid > 0)
            {
                $new_paid_amount = $invoiceInfo[0]->paid + $new_payment_value;
                // update the paid amount 
                $this->homeRepository->updateInvoicePaidAmount($invoiceInfo[0]->invoice_id, $new_paid_amount);
            } 
        }  


        // update the status of the invoice in case it is changed
        if(!is_null($request->input('invoice_status')))
        $this->homeRepository->updateInvoiceStatus($invoiceInfo[0]->invoice_id, $request->input('invoice_status'));
        $request->session()->flash('flash_message','Invoice Successfully updated!');

        //gets all invoices and their information 
        $invoicesList = $this->homeRepository->getInvoices();

        return view('invoices.index', array('invoicesList' => $invoicesList));
    }


    public function printInvoice($invoice_id)
    {
        //gets all invoices and their information 
        $invoiceInfo = $this->homeRepository->getInvoice($invoice_id);
        $invoiceItems = $this->homeRepository->getInvoiceItems($invoice_id);
        $data = array_merge($invoiceInfo, $invoiceItems);
        return view('invoices.print-invoice', array('data' => $data));
    }

    public function sendInvoice(Request $request, $invoice_id)
    {
        
        //gets all invoices and their information 
        $invoiceInfo = $this->homeRepository->getInvoice($invoice_id);
        $invoiceItems = $this->homeRepository->getInvoiceItems($invoice_id);
        $data = array_merge($invoiceInfo, $invoiceItems);
        $pdf = PDF::loadView('invoices.print-invoice', array('data' => $data))->setPaper('a4');

        $client_email = $invoiceInfo[0]->email;

        Mail::send('invoices.email-invoice', array('data' => $data), function($message) use($pdf, $client_email, $invoice_id)
        {
            $message->from('info@webneoo.com', 'Webneoo');
            $message->to($client_email)->subject('Invoice # '.$invoice_id. ' from webneoo');
            $message->attachData($pdf->output(), "invoice.pdf");
        });

        $this->homeRepository->updateInvoiceStatus($invoice_id, 1);

        $request->session()->flash('flash_message','Invoice Successfully Sent!');
        //gets all invoices and their information 
        $invoicesList = $this->homeRepository->getInvoices();
        return view('invoices.index', array('invoicesList' => $invoicesList));
    }

    public function downloadInvoice($invoice_id)
    {
        //gets all invoices and their information 
        $invoiceInfo = $this->homeRepository->getInvoice($invoice_id);
        $invoiceItems = $this->homeRepository->getInvoiceItems($invoice_id);
        $data = array_merge($invoiceInfo, $invoiceItems);
        $pdf = PDF::loadView('invoices.print-invoice', array('data' => $data))->setPaper('a4');
        return $pdf->download('invoice.pdf');
    }

    public function hideInvoice(Request $request, $invoice_id)
    {
        $this->homeRepository->hideInvoice($invoice_id);

        //gets all invoices and their information 
        $invoicesList = $invoicesList = $this->homeRepository->getInvoices();
        $request->session()->flash('flash_message','Invoice Successfully removed!');

        return view('invoices.index', array('invoicesList' => $invoicesList));
    }


    /* --------------------------------
    TRANSACTIONS
    -----------------------------------*/

    public function transactions()
    {
        // if the user is not admin
        if (\Auth::user()->role_id != 1)
         // get the accountant transactions list
            $transactionsList = $this->homeRepository->getTransactions();
        // if the user is admin
        else
        // get all transactions
            $transactionsList = $this->homeRepository->getAllTransactions();

        return view('transactions.index', array('transactionsList' => $transactionsList));
    }

    public function transactionDetails($transaction_id)
    {
        //gets all information of a specific transaction
        $transactionInfo = $this->homeRepository->getTransaction($transaction_id);

        return view('transactions.transaction-details', array('transactionInfo' => $transactionInfo));
    }

    public function addTransaction()
    {   
        $all_sources = $this->homeRepository->getAllSources();

        // get the list of all the categories in the database
        $category_list = $this->homeRepository->getAllCategories();

        return view('transactions.add-transaction', array('all_sources' => $all_sources,'category_list' => $category_list));
    }

    public function storeTransaction(Request $request)
    {   
        //if the submit button of the add transaction page is clicked, validate the input and insert them in the database
        $this->validate($request, [ 'transaction_source' => 'required', 
                                    'transaction_amount' => 'required',
                                    'transaction_description' => 'required',
                                    'transaction_category_id' => 'required',
                                    'transaction_date' => 'required',
                                    'transaction_type' => 'required']); 
        $this->homeRepository->addTransaction(null, $request->input('transaction_invoice'), $request->input('transaction_source'), $request->input('transaction_amount'), 
                                              $request->input('transaction_description'), $request->input('transaction_category_id'), $request->input('transaction_date'), 
                                              $request->input('transaction_type'));
        

        $request->session()->flash('flash_message','Transaction Successfully added!');

        // if the user is not admin
        if (\Auth::user()->role_id != 1)
         // get the accountant transactions list
            $transactionsList = $this->homeRepository->getTransactions();
        // if the user is admin
        else
        // get all transactions
            $transactionsList = $this->homeRepository->getAllTransactions();


        return view('transactions.index', array('transactionsList' => $transactionsList));
    }

    public function editTransaction($transaction_id)
    {
        //gets all information of a specific transaction
        $transactionInfo = $this->homeRepository->getTransaction($transaction_id);

        // get the list of all the categories in the database
        $category_list = $this->homeRepository->getAllCategories();

        return view('transactions.edit-transaction', 
                    array('transaction_id' => $transaction_id, 'transactionInfo' => $transactionInfo, 'category_list' => $category_list));
    }

  
    public function hideTransaction($transaction_id)
    {
        $this->homeRepository->hideTransaction($transaction_id);

        // if the user is not admin
        if (\Auth::user()->role_id != 1)
         // get the accountant transactions list
            $transactionsList = $this->homeRepository->getTransactions();
        // if the user is admin
        else
        // get all transactions
            $transactionsList = $this->homeRepository->getAllTransactions();

        $request->session()->flash('flash_message','Transaction Successfully removed!');

        return view('transactions.index', array('transactionsList' => $transactionsList));
    }




    /* --------------------------------
    SALARIES
    -----------------------------------*/

    public function team()
    {
        
        // get all the info of the team
        $teamInfo = $this->homeRepository->getTeamInfo();

        return view('team.index', array('teamInfo' => $teamInfo));
    }


    public function profileDetails($user_id)
    {
        
        // get the info of a specific user
        $user_info = $this->homeRepository->getUserInfo($user_id);

        return view('team.profile-details', array('user_info' => $user_info));
    }





    /* --------------------------------
    TRANSPORTATION
    -----------------------------------*/

    public function transportation($user_id)
    {
        
        // get the info of a specific user
        $user_info = $this->homeRepository->getUserInfo($user_id);

        // get the transportation of a specific user
        $transportation_info = $this->homeRepository->getTransportation($user_id);

        return view('team.transportation', array('user_info' => $user_info, 'transportation_info' => $transportation_info));
    }


    public function storeTransportation(Request $request, $user_id)
    {
        
        //if the submit button of the add transportation page is clicked, validate the input and insert them in the database
        $this->validate($request, [ 'transport_date' => 'required', 
                                    'transport_place' => 'required',
                                    'transport_reason' => 'required',
                                    'transport_price' => 'required']); 
        //add the new transporation
        $this->homeRepository->addTransportation($request->only('transport_date', 'transport_place', 'transport_reason', 'transport_price'), $user_id);
    
        $request->session()->flash('flash_message','Transportation successfully added!');

        // get the info of a specific user
        $user_info = $this->homeRepository->getUserInfo($user_id);


        // get the transportation of a specific user
        $transportation_info = $this->homeRepository->getTransportation($user_id);
        

        return view('team.transportation', array('user_info' => $user_info, 'transportation_info' => $transportation_info));
    }



    public function salary($user_id)
    {
        
        // get the info of a specific user
        $user_info = $this->homeRepository->getUserInfo($user_id);

        return view('team.salary', array('user_info' => $user_info));
    }



    public function previewSalary(Request $request, $user_id)
    {   

        // get the info of a specific user
        $user_info = $this->homeRepository->getUserInfo($user_id);

       // get the total transportation for the selected month for a specific user
        $transport_amount = $this->homeRepository->calculateMonthlyTranportation($user_id, $request->transport_date);
        if($transport_amount != NULL)
        $transport_amount = (float)$transport_amount[0]->transport_amount;
        else
        $transport_amount = 0;

       

        // calculating the amount to reduce from the days off
        if(isset($request->days_off) || $request->days_off != 0)
        $days_off_amount = ($user_info[0]->base_salary / 20.0)* $request->days_off;
        else
        $days_off_amount = 0;

        $bonus_amount = (float)$request->bonus;
        $base_salary_amount = (float)$user_info[0]->base_salary;

        $total_amount = ($base_salary_amount + $transport_amount - $days_off_amount + $bonus_amount);


        return view('team.salary', array('user_info' => $user_info, 'base_salary_amount' => $base_salary_amount, 'transport_amount' => $transport_amount, 
                                         'days_off_amount' => $days_off_amount, 'bonus_amount' => $bonus_amount, 'total_amount' => $total_amount, 'month' => $request->transport_date));
        
    }



    public function storeSalary(Request $request, $user_id)
    {   

        // get the info of a specific user
        $user_info = $this->homeRepository->getUserInfo($user_id);

        $base_salary_amount = (float)$request->base_salary_amount;
        $transport_amount = (float)$request->transport_amount;
        $days_off_amount = (float)$request->days_off_amount;
        $bonus_amount = (float)$request->bonus_amount;
        $total_amount = (float)$request->total_amount;
        $description = $request->description;
        $salary_date = $request->transport_date;

        //store the salary as a transaction
        $this->homeRepository->addSalary($base_salary_amount, $transport_amount, $days_off_amount, 
                                         $bonus_amount, $total_amount, $description, $salary_date, $user_id);
                                                      
       
        // if the user is not admin
        if (\Auth::user()->role_id != 1)
         // get the accountant transactions list
            $transactionsList = $this->homeRepository->getTransactions();
        // if the user is admin
        else
        // get all transactions
            $transactionsList = $this->homeRepository->getAllTransactions();


          $request->session()->flash('flash_message','Salary Successfully added!');

        return view('transactions.index', array('transactionsList' => $transactionsList));
        
    }

    




    



}
