<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\InvoiceRepository;
use App\Http\Repositories\TransactionRepository;
use App\Http\Repositories\ClientRepository;
use PDF;
use Mail;




class InvoiceController extends Controller
{
	private $invoiceRepository;
    private $transactionRepository;
    private $clientRepository;


    public function __construct(InvoiceRepository $invoiceRepository, TransactionRepository $transactionRepository, ClientRepository $clientRepository)
    {
    	$this->invoiceRepository = $invoiceRepository;
        $this->transactionRepository = $transactionRepository;
        $this->clientRepository = $clientRepository;
        $this->middleware('auth');
    }


    /*---------------------------------------------------------
    Shows the list of invoices with its main information
    -------------------------------------------------------------*/
    public function index()
    {
        //gets all invoices and their information 
        $invoicesList = $this->invoiceRepository->getInvoices();
        return view('invoices.index', array('invoicesList' => $invoicesList));
    }

    /*---------------------------------------------------------
    Shows the details of a specific invoice
    -------------------------------------------------------------*/
    public function show($invoice_id)
    {
        //get invoice items
        $invoiceItems = $this->invoiceRepository->getInvoiceItems($invoice_id);
        //gets all information of a specific invoice
        $invoiceInfo = $this->invoiceRepository->getInvoiceById($invoice_id);
        $data = array_merge($invoiceInfo, $invoiceItems);

        return view('invoices.invoice-details', array('data' => $data));
    }

    /*---------------------------------------------------------
    Show the form to add an invoice
    -------------------------------------------------------------*/    
    public function add()
    {
        //gets all clients 
        $clients = $this->clientRepository->getAll();
        //gets all services
        $services = $this->invoiceRepository->getServices();
        return view('invoices.add-invoice', array('clients' => $clients, 'services' => $services));
    }

    /*---------------------------------------------------------
    Add an invoice in the system
    -------------------------------------------------------------*/
    public function store(Request $request)
    {
        //Initialization of amount of the invoice
        $amount = 0;

        //Parsing all the items of the invoice that we need to add
        for($i=1;$i<=($request->item_number);$i++)
        {
            $amount = $amount + $request->input('invoice_item_amount_'.$i);
        }

        //if the submit button of the add invoice page is clicked, validate the input and insert them in the database
        $this->validate($request, ['invoice_client' => 'required',
                                    'invoice_date' => 'required']);

        //If this is a normal invoice
        if($request->is_black == 0)
        {
            //Incrementation of the last invoice number
            $lastInvoiceNb = $this->invoiceRepository->getLastInvoiceNb();
            $invoiceNb = $lastInvoiceNb[0]->invoice_nb + 1;
        }
        else //If this is a black invoice
            $invoiceNb = 0; //The invoice number should be marked as 0
        

        //Add the invoice
        $this->invoiceRepository->addInvoice($invoiceNb, $request->only('invoice_client', 'invoice_date', 'is_black'), $amount);

        $lastInvoiceId = $this->invoiceRepository->getLastInvoiceId();

        for($i=1;$i<=($request->item_number);$i++)
        {
            //if the submit button of the add invoice page is clicked, validate the input and insert them in the database
            $this->validate($request, ['invoice_item_service_'.$i => 'required', 
                                        'invoice_item_description_'.$i => 'required', 
                                        'invoice_item_amount_'.$i => 'required']);
            $this->invoiceRepository->addInvoiceItems($lastInvoiceId[0]->invoice_id, $request->input('invoice_item_service_'.$i), $request->input('invoice_item_description_'.$i), $request->input('invoice_item_amount_'.$i));
        }

        $request->session()->flash('flash_message','Invoice Successfully added!');

        //gets all invoices and their information 
        $invoicesList = $this->invoiceRepository->getInvoices();

        return view('invoices.index', array('invoicesList' => $invoicesList));
    }

    /*---------------------------------------------------------
    Show the form to edit an invoice
    -------------------------------------------------------------*/
    public function edit($invoice_id)
    {   
        //gets all clients 
        $clients = $this->clientRepository->getAll();

        //gets all statuses
        $status = $this->invoiceRepository->getAllInvoiceStatus();

        //get invoice items
        $invoiceItems = $this->invoiceRepository->getInvoiceItems($invoice_id);

        //gets all information of a specific invoice
        $invoiceInfo = $this->invoiceRepository->getInvoiceById($invoice_id);

        //gets all the paid transactions for a specific invoice id
        $invoiceTransactions = $this->transactionRepository->getTransactionFromInvoiceId($invoice_id);

        return view('invoices.edit-invoice', array('invoice_id' => $invoice_id, 'clients' => $clients, 'status' => $status, 'invoiceItems' => $invoiceItems, 
                                                   'invoiceInfo' => $invoiceInfo, 'invoiceTransactions' => $invoiceTransactions));
    }

    /*---------------------------------------------------------
    Edit an invoice which is a draft
    -------------------------------------------------------------*/
    public function updateDraft(Request $request)
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
            $this->invoiceRepository->updateInvoiceItems($request->input('invoice_item_id_'.$i), $request->input('invoice_item_description_'.$i), $request->input('invoice_item_amount_'.$i));
            //Store the total amount of the invoice
            $amount = $amount + $request->input('invoice_item_amount_'.$i);
        }

        //if the submit button of the edit invoice page is clicked, validate the input and update them in the database
        $this->validate($request, ['invoice_id' => 'required', 
                                    'invoice_date' => 'required']);

        //Update the invoice with the final amount calculated above
        $this->invoiceRepository->updateDraftInvoice($request->only('invoice_id', 'invoice_client', 'invoice_date', 'invoice_paid'), $amount);
        $request->session()->flash('flash_message','Invoice Successfully updated!');

        //gets all invoices and their information 
        $invoicesList = $this->invoiceRepository->getInvoices();


        return view('invoices.index', array('invoicesList' => $invoicesList));
    }


    /*---------------------------------------------------------
    Edit an invoice which is not draft
    -------------------------------------------------------------*/
    public function updateNoDraft(Request $request)
    {   
        //gets all information of a specific invoice
        $invoiceInfo = $this->invoiceRepository->getInvoiceById($request->invoice_id);
        //If we want to submit a new payment for the invoice
        if(isset($request->add_payment_submit)){
            //Verify that the amount submited is smaller or equal to the remaining due amount in the invoice
            if($request->payment_value <= $invoiceInfo[0]->remaining) 
            {
                //concatenate the description in a variable
                $description = $request->payment_value.' USD payed from the invoice number '.$invoiceInfo[0]->invoice_id;
                $actual_date = date('Y-m-d H:i:s');

                //Add an income transaction to the system
                $this->transactionRepository->addTransaction($invoiceInfo[0]->invoice_id, $invoiceInfo[0]->client_id, 
                                                        $request->payment_value, $description, 10, $actual_date, 1);

                    
                //If the total amount has been paid
                if($invoiceInfo[0]->remaining == $request->payment_value)
                    //Update the invoice status to Paid
                    $this->invoiceRepository->updateInvoiceStatus($invoiceInfo[0]->invoice_id, 3);
                else //If a payment has been done but is incomplete
                    $this->invoiceRepository->updateInvoiceStatus($invoiceInfo[0]->invoice_id, 2);

                

                $request->session()->flash('flash_message','Invoice Successfully updated!');
                //gets all invoices and their information 
                $invoicesList = $this->invoiceRepository->getInvoices();
                return view('invoices.index', array('invoicesList' => $invoicesList));
            }
            else{
                $request->session()->flash('flash_message','Value bigger than total amount.');
                //gets all invoices and their information 
                $invoicesList = $this->invoiceRepository->getInvoices();
                return view('invoices.index', array('invoicesList' => $invoicesList));
            }
        }
        //If the payment of the invoice is totally done
        elseif (isset($request->close_invoice_submit)) {
            //concatenate the description in a variable
            $description = $invoiceInfo[0]->remaining.' USD payed from the invoice number '.$invoiceInfo[0]->invoice_id;
            $actual_date = date('Y-m-d H:i:s');

            //Add an income transaction to the system
            $this->transactionRepository->addTransaction($invoiceInfo[0]->invoice_id, $invoiceInfo[0]->client_id, 
                                                        $invoiceInfo[0]->remaining, $description, 10, $actual_date, 1);

            //Update the invoice status to Paid
            $this->invoiceRepository->updateInvoiceStatus($invoiceInfo[0]->invoice_id, 3);
            
            $request->session()->flash('flash_message','Invoice Successfully updated!');
            //gets all invoices and their information 
            $invoicesList = $this->invoiceRepository->getInvoices();
            return view('invoices.index', array('invoicesList' => $invoicesList));
        }
        //If we want to cancel the invoice not paid by the client
        elseif (isset($request->cancel_invoice_submit)) {

            //Update the invoice status to Paid
            $this->invoiceRepository->updateInvoiceStatus($invoiceInfo[0]->invoice_id, 5);

            $request->session()->flash('flash_message','Invoice Successfully updated!');
            //gets all invoices and their information 
            $invoicesList = $this->invoiceRepository->getInvoices();
            return view('invoices.index', array('invoicesList' => $invoicesList));
        }

    }


    /*---------------------------------------------------------
    Print an invoice
    -------------------------------------------------------------*/
    public function printt($invoice_id)
    {
        //gets all invoices and their information 
        $invoiceInfo = $this->invoiceRepository->getInvoiceById($invoice_id);
        $invoiceItems = $this->invoiceRepository->getInvoiceItems($invoice_id);
        $data = array_merge($invoiceInfo, $invoiceItems);
        return view('invoices.print-invoice', array('data' => $data));
    }

    /*---------------------------------------------------------
    Send an invoice to client
    -------------------------------------------------------------*/
    public function send(Request $request, $invoice_id)
    {
        
        //gets all invoices and their information 
        $invoiceInfo = $this->invoiceRepository->getInvoiceById($invoice_id);
            //If the invoice is not a black invoice, we must send the email to the client
        if($invoiceInfo[0]->invoice_nb != 0)
        {
            $invoiceItems = $this->invoiceRepository->getInvoiceItems($invoice_id);
            $data = array_merge($invoiceInfo, $invoiceItems);

            $pdf = PDF::loadView('invoices.print-invoice', array('data' => $data))->setPaper('a4');

            $client_email = $invoiceInfo[0]->email;
            $bcc_emails = ['info@webneoo.com'];

            Mail::send('invoices.email-invoice', array('data' => $data), function($message) use($pdf, $client_email, $invoice_id, $bcc_emails)
            {
                $message->from('info@webneoo.com', 'Webneoo');
                $message->to($client_email)->subject('Invoice # '.$invoice_id. ' from webneoo');
                $message->bcc($bcc_emails, 'Accounting system');
                $message->attachData($pdf->output(), "invoice.pdf");
            });

        }
        

        //Update the status o the invoice to SENT
        $this->invoiceRepository->updateInvoiceStatus($invoice_id, 1);

        $request->session()->flash('flash_message','Invoice Successfully Sent!');
        //gets all invoices and their information 
        $invoicesList = $this->invoiceRepository->getInvoices();
        return view('invoices.index', array('invoicesList' => $invoicesList));
    }


    /*---------------------------------------------------------
    Download an invoice
    -------------------------------------------------------------*/
    public function download($invoice_id)
    {
        //gets all invoices and their information 
        $invoiceInfo = $this->invoiceRepository->getInvoiceById($invoice_id);
        $invoiceItems = $this->invoiceRepository->getInvoiceItems($invoice_id);
        $data = array_merge($invoiceInfo, $invoiceItems);
        $pdf = PDF::loadView('invoices.print-invoice', array('data' => $data))->setPaper('a4');
        $file_name = 'invoice '.$invoice_id.'.pdf';
        return $pdf->download($file_name);
    }


    /*---------------------------------------------------------
    Hide an invoice
    -------------------------------------------------------------*/
    public function hide(Request $request, $invoice_id)
    {
        $this->invoiceRepository->hideInvoice($invoice_id);

        //gets all invoices and their information 
        $invoicesList = $invoicesList = $this->invoiceRepository->getInvoices();
        $request->session()->flash('flash_message','Invoice Successfully removed!');

        return view('invoices.index', array('invoicesList' => $invoicesList));
    }


}
