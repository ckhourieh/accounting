<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\HomeRepository;
use Session;
use Mail;
use PDF;

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
        $socialMediaTotalDue = $this->homeRepository->getServiceDueInvoices(1);
        $webDevelopmentTotalDue = $this->homeRepository->getServiceDueInvoices(2);
        $emailsHostingTotalDue = $this->homeRepository->getServiceDueInvoices(3);
        return view('dashboard', array('socialMediaTotalDue' => $socialMediaTotalDue, 'webDevelopmentTotalDue' => $webDevelopmentTotalDue, 'emailsHostingTotalDue' => $emailsHostingTotalDue));
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
                                    'client_contact' => 'required',
                                    'client_accounting' => 'required']);
        $this->homeRepository->addClient($request->only('client_name', 'client_desc', 'client_email', 'client_phone', 'client_address', 'client_owner', 'client_contact', 'client_accounting'));
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
        //if the submit button of the edit client page is clicked, validate the input and update them in the database
        $this->validate($request, ['source_id' => 'required',
                                    'source_name' => 'required', 
                                    'source_desc' => 'required', 
                                    'source_email' => 'required',
                                    'source_phone' => 'required',
                                    'source_address' => 'required',
                                    'source_owner' => 'required',
                                    'source_contact' => 'required',
                                    'source_accounting' => 'required']);
        $this->homeRepository->updateSource($request->only('source_id', 'source_name', 'source_desc', 'source_email', 'source_phone', 'source_address', 'source_owner', 'source_contact', 'source_accounting'));
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
                                    'supplier_contact' => 'required',
                                    'supplier_accounting' => 'required']);
        $this->homeRepository->addSupplier($request->only('supplier_name', 'supplier_desc', 'supplier_email', 'supplier_phone', 'supplier_address', 'supplier_owner', 'supplier_contact', 'supplier_accounting'));
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
                                    'source_contact' => 'required',
                                    'source_accounting' => 'required']);
        $this->homeRepository->updateSource($request->only('source_id', 'source_name', 'source_desc', 'source_email', 'source_phone', 'source_address', 'source_owner', 'source_contact', 'source_accounting'));
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
        $amount = 0;
        //dd($request->all());
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

        return view('invoices.edit-invoice', array('invoice_id' => $invoice_id, 'clients' => $clients, 'status' => $status, 'invoiceItems' => $invoiceItems, 'invoiceInfo' => $invoiceInfo));
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
                                    'invoice_client' => 'required',
                                    'invoice_date' => 'required',
                                    'invoice_status' => 'required',
                                    'invoice_paid' => 'required']);
        //Update the invoice with the final amount calculated above
        $this->homeRepository->updateInvoice($request->only('invoice_id', 'invoice_client', 'invoice_date', 'invoice_status', 'invoice_paid'), $amount);
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

        Mail::send('invoices.email-invoice', array('data' => $data), function($message) use($pdf, $client_email)
        {
            $message->from('info@webneoo.com', 'Webneoo');
            $message->to($client_email)->subject('Webneoo Invoice');
            $message->attachData($pdf->output(), "invoice.pdf");
        });

        $this->homeRepository->updateInvoiceStatusToSent($invoice_id);

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

    public function hideInvoice($invoice_id)
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
        //gets all transactions and their information 
        $transactionsList = $this->homeRepository->getTransactions();
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
        return view('transactions.add-transaction');
    }

    public function storeTransaction(Request $request)
    {
        //if the submit button of the add transaction page is clicked, validate the input and insert them in the database
        $this->validate($request, ['transaction_invoice' => 'required', 
                                    'transaction_source' => 'required', 
                                    'transaction_amount' => 'required',
                                    'transaction_contact' => 'required',
                                    'transaction_date' => 'required',
                                    'transaction_type' => 'required']);
        $this->homeRepository->addTransaction($request->only('transaction_invoice', 'transaction_source', 'transaction_amount', 'transaction_contact', 'transaction_date', 'transaction_type'));
        $request->session()->flash('flash_message','Transaction Successfully added!');

        //gets all transactions and their information 
        $transactionsList = $this->homeRepository->getTransactions();

        return view('transactions.index', array('transactionsList' => $transactionsList));
    }

    public function editTransaction($transaction_id)
    {
        //gets all information of a specific transaction
        $transactionInfo = $this->homeRepository->getTransaction($transaction_id);
        return view('transactions.edit-transaction', array('transaction_id' => $transaction_id, 'transactionInfo' => $transactionInfo));
    }

    public function updateTransaction(Request $request)
    {
        //if the submit button of the edit transaction page is clicked, validate the input and update them in the database
        $this->validate($request, ['transaction_id' => 'required',
                                    'transaction_invoice' => 'required', 
                                    'transaction_source' => 'required', 
                                    'transaction_amount' => 'required',
                                    'transaction_contact' => 'required',
                                    'transaction_date' => 'required',
                                    'transaction_type' => 'required']);
        $this->homeRepository->updateTransaction($request->only('transaction_id', 'transaction_invoice', 'transaction_source', 'transaction_amount', 'transaction_contact', 'transaction_date', 'transaction_type'));
        $request->session()->flash('flash_message','Transaction Successfully updated!');

        //gets all transactions and their information 
        $transactionsList = $this->homeRepository->getTransactions();

        return view('transactions.index', array('transactionsList' => $transactionsList));
    }

    public function hideTransaction($transaction_id)
    {
        $this->homeRepository->hideTransaction($transaction_id);

        //gets all transactions and their information 
        $transactionsList = $this->homeRepository->getTransactions();
        $request->session()->flash('flash_message','Transaction Successfully removed!');

        return view('transactions.index', array('transactionsList' => $transactionsList));
    }

}
