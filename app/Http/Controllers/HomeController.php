<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\HomeRepository;
use Session;
use Mail;

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
        return view('dashboard');
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
        $clientInfo = $this->homeRepository->getClient($client_id);
        return view('clients.client-details', array('clientInfo' => $clientInfo));
    }

    public function totalAmountDue($client_id)
    {
        //gets all information of a specific client
        $clientInfo = $this->homeRepository->getClientTotalAmountDue($client_id);
        return view('clients.total-amount-due', array('clientInfo' => $clientInfo));
    }

    public function totalIncome($client_id)
    {
        //gets all information of a specific client
        $clientInfo = $this->homeRepository->getClientTotalIncome($client_id);
        return view('clients.total-income', array('clientInfo' => $clientInfo));
    }

    public function nextPayments($client_id)
    {
        //gets all information of a specific client
        $clientInfo = $this->homeRepository->getClientNextPayments($client_id);
        return view('clients.nextpayments', array('clientInfo' => $clientInfo));
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
        $clientInfo = $this->homeRepository->getClient($client_id);
        return view('clients.edit-client', array('client_id' => $client_id, 'clientInfo' => $clientInfo));
    }

    public function updateClient(Request $request)
    {
        //if the submit button of the edit client page is clicked, validate the input and update them in the database
        $this->validate($request, ['client_id' => 'required',
                                    'client_name' => 'required', 
                                    'client_desc' => 'required', 
                                    'client_email' => 'required',
                                    'client_phone' => 'required',
                                    'client_address' => 'required',
                                    'client_owner' => 'required',
                                    'client_contact' => 'required',
                                    'client_accounting' => 'required']);
        $this->homeRepository->updateClient($request->only('client_id', 'client_name', 'client_desc', 'client_email', 'client_phone', 'client_address', 'client_owner', 'client_contact', 'client_accounting'));
        $request->session()->flash('flash_message','Client Successfully updated!');

        //gets all clients and their information 
        $clientsList = $this->homeRepository->getClients();
        return view('clients.index', array('clientsList' => $clientsList));
    }

    public function hideClient($client_id)
    {
        $this->homeRepository->hideClient($client_id);

        //gets all clients and their information 
        $clientsList = $this->homeRepository->getClients();
        $request->session()->flash('flash_message','Client Successfully removed!');

        return view('clients.index', array('clientsList' => $clientsList));
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
        return view('invoices.invoice-details', array('invoiceItems' => $invoiceItems, 'invoiceInfo' => $invoiceInfo));
    }

    public function addInvoice()
    {
        //gets all clients 
        $clients = $this->homeRepository->getClientNames();

        //gets all frequencies 
        $frequencies = $this->homeRepository->getFrequencies();

        return view('invoices.add-invoice', array('clients' => $clients, 'frequencies' => $frequencies));
    }

    public function storeInvoice(Request $request)
    {
        $amount = 0;
        for($i=1;$i<=((count($request->all())-6)/2);$i++)
        {
            //if the submit button of the add invoice page is clicked, validate the input and insert them in the database
            $this->validate($request, ['invoice_id' => 'required', 
                                        'invoice_item_title_'.$i => 'required', 
                                        'invoice_item_amount_'.$i => 'required']);
            $this->homeRepository->addInvoiceItems($request->input('invoice_id'), $request->input('invoice_item_title_'.$i), $request->input('invoice_item_amount_'.$i));
            $amount = $amount + $request->input('invoice_item_amount_'.$i);
        }

        //if the submit button of the add invoice page is clicked, validate the input and insert them in the database
        $this->validate($request, ['invoice_id' => 'required', 
                                    'invoice_title' => 'required', 
                                    'invoice_client' => 'required',
                                    'invoice_date' => 'required',
                                    'invoice_frequency' => 'required']);
        $this->homeRepository->addInvoice($request->only('invoice_id', 'invoice_title', 'invoice_client', 'invoice_date', 'invoice_frequency'), $amount);
        $request->session()->flash('flash_message','Invoice Successfully added!');

        //gets all invoices and their information 
        $invoicesList = $this->homeRepository->getInvoices();

        return view('invoices.index', array('invoicesList' => $invoicesList));
    }

    public function editInvoice($invoice_id)
    {
        //gets all clients 
        $clients = $this->homeRepository->getClientNames();

        //gets all frequencies 
        $frequencies = $this->homeRepository->getFrequencies();

        //get invoice items
        $invoiceItems = $this->homeRepository->getInvoiceItems($invoice_id);

        //gets all information of a specific invoice
        $invoiceInfo = $this->homeRepository->getInvoice($invoice_id);

        return view('invoices.edit-invoice', array('invoice_id' => $invoice_id, 'clients' => $clients, 'frequencies' => $frequencies, 'invoiceItems' => $invoiceItems, 'invoiceInfo' => $invoiceInfo));
    }

    public function updateInvoice(Request $request)
    {
        $amount = 0;
        for($i=1;$i<=((count($request->all())-9)/3);$i++)
        {
            //if the submit button of the add invoice page is clicked, validate the input and insert them in the database
            $this->validate($request, ['invoice_item_id_'.$i => 'required',
                                        'invoice_item_title_'.$i => 'required', 
                                        'invoice_item_amount_'.$i => 'required']);
            $this->homeRepository->updateInvoiceItems($request->input('invoice_item_id_'.$i), $request->input('invoice_item_title_'.$i), $request->input('invoice_item_amount_'.$i));
            $amount = $amount + $request->input('invoice_item_amount_'.$i);
        }

        //if the submit button of the edit invoice page is clicked, validate the input and update them in the database
        $this->validate($request, ['id' => 'required',
                                    'invoice_id' => 'required', 
                                    'invoice_title' => 'required', 
                                    'invoice_client' => 'required',
                                    'invoice_date' => 'required',
                                    'invoice_frequency' => 'required',
                                    'invoice_status' => 'required',
                                    'invoice_paid' => 'required']);
        $this->homeRepository->updateInvoice($request->only('id', 'invoice_id', 'invoice_title', 'invoice_client', 'invoice_date', 'invoice_frequency', 'invoice_status', 'invoice_paid'), $amount);
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
        return view('invoices.print-invoice', array('invoice_id' => $invoice_id,'invoiceInfo' => $invoiceInfo, 'invoiceItems' => $invoiceItems));
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
