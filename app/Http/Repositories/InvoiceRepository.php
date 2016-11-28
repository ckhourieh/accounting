<?php namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InvoiceRepository {

    /*----------------------------------
    Get all invoices and their information
    ------------------------------------*/
    public function getInvoices()
    {
        \DB::transaction(function() {
            //Select all invoices where due date is passed and status is not paid
            $invoices = \DB::select("SELECT * FROM ta_invoices WHERE due_date < CURDATE() AND status_id = 1");
            
            //Set the status of all these invoices to Overdue
            foreach($invoices as $i)
            {
                \DB::table('ta_invoices')
                ->where('invoice_id', $i->invoice_id)
                ->update(['status_id' => '4',
                'updated_at' => Carbon::now('Asia/Beirut')]);
            }
        });


        $q = \DB::select("SELECT A.*, IFNULL(B.paid,0) as paid, (A.amount - IFNULL(B.paid,0)) as remaining
                          FROM
                            (SELECT A.invoice_id, A.invoice_nb, A.due_date, A.client_id, A.amount, DATE(A.created_at) as created_at, A.status_id, 
                                    B.accounting_id, B.name as client_name, B.email, B.address, 
                                    B.contact_name, B.phone, C.name as status_name, C.color_code, A.hidden
                            FROM ta_invoices as A 
                            JOIN ta_sources as B ON A.client_id = B.source_id AND B.hidden = 0
                            JOIN ta_status as C ON A.status_id = C.status_id
                            WHERE A.hidden = '0') as A

                           LEFT JOIN

                            (SELECT A.invoice_id, SUM(A.amount) as paid
                            FROM ta_transactions as A
                            WHERE A.type = 1
                            AND A.hidden = 0
                            GROUP BY A.invoice_id) as B

                            ON A.invoice_id = B.invoice_id");
        return $q;
    }


    /*----------------------------------
    Get a specific invoice's information
    ------------------------------------*/
    public function getInvoiceById($invoice_id)
    {
        $q = \DB::select("SELECT A.*, IFNULL(B.paid,0) as paid, (A.amount - IFNULL(B.paid,0)) as remaining
                          FROM
                            (SELECT A.invoice_id, A.invoice_nb, A.due_date, A.client_id, A.amount, DATE(A.created_at) as created_at, A.status_id, 
                                    B.accounting_id, B.name as client_name, B.email, B.address, 
                                    B.contact_name, B.phone, C.name as status_name, C.color_code, A.hidden
                            FROM ta_invoices as A 
                            JOIN ta_sources as B ON A.client_id = B.source_id AND B.hidden = 0
                            JOIN ta_status as C ON A.status_id = C.status_id
                            WHERE A.hidden = '0') as A

                           LEFT JOIN

                            (SELECT A.invoice_id, SUM(A.amount) as paid
                            FROM ta_transactions as A
                            WHERE A.type = 1
                            AND A.hidden = 0
                            GROUP BY A.invoice_id) as B

                          ON A.invoice_id = B.invoice_id
                          WHERE A.hidden = '0' AND A.invoice_id = :invoice_id",
            array(':invoice_id' => $invoice_id));
        return $q;
    }


    /*----------------------------------
    Get Invoice Items
    ------------------------------------*/
    public function getInvoiceItems($invoice_id)
    {
        $q = \DB::select("SELECT B.invoice_item_id, B.description, B.amount as item_amount, C.service_type_id, C.title as service_title
                          FROM ta_invoices as A
                          JOIN ta_invoice_items as B ON A.invoice_id = B.invoice_id
                          JOIN ta_service_types as C ON B.service_type_id = C.service_type_id
                          WHERE A.hidden = '0' AND A.invoice_id = :invoice_id
                          ORDER BY C.service_type_id",
            array(':invoice_id' => $invoice_id));
        return $q;
    }

    /*----------------------------------
    Get last invoice id
    ------------------------------------*/
    public function getLastInvoiceId()
    {
        $q = \DB::select("SELECT invoice_id FROM ta_invoices ORDER BY invoice_id DESC LIMIT 1");
        return $q;
    }

    /*----------------------------------
    Get last invoice Number
    ------------------------------------*/
    public function getLastInvoiceNb()
    {
        $q = \DB::select("SELECT invoice_nb FROM ta_invoices ORDER BY invoice_nb DESC LIMIT 1");
        return $q;
    }

    
    /*----------------------------------
    Get Invoice Services
    ------------------------------------*/
    public function getServices()
    {
        $q = \DB::select("SELECT service_type_id, title FROM ta_service_types ORDER BY service_type_id");
        return $q;
    }

    /*----------------------------------
    Add a new invoice
    ------------------------------------*/
    public function addInvoice($invoiceNb, $input, $amount)
    {
          \DB::table('ta_invoices')->insert([
              'invoice_nb' => $invoiceNb,
              'client_id' => $input['invoice_client'],
              'amount' => $amount,
              'due_date' => $input['invoice_date'],
              'status_id' => '6',
              'is_black' => $input['is_black'],
              'hidden' => '0',
              'created_at' => Carbon::now('Asia/Beirut'),
              'updated_at' => Carbon::now('Asia/Beirut')
          ]); 
    }

    /*----------------------------------
    Add invoice items
    ------------------------------------*/
    public function addInvoiceItems($lastInvoiceId, $invoice_item_service, $invoice_item_description, $invoice_item_amount)
    {
        \DB::table('ta_invoice_items')->insert([
            'description' => $invoice_item_description, 
            'service_type_id' => $invoice_item_service,
            'amount' => $invoice_item_amount,
            'invoice_id' => $lastInvoiceId,
            'created_at' => Carbon::now('Asia/Beirut'),
            'updated_at' => Carbon::now('Asia/Beirut')
        ]);
    }



    /*----------------------------------
    Update Draft Invoices (keep status the same)
    ------------------------------------*/
    public function updateDraftInvoice($input, $amount)
    {   
        \DB::transaction(function() use ($input, $amount) {

            
            //Update the invoice data
            \DB::table('ta_invoices')
                ->where('invoice_id', $input['invoice_id'])
                ->update(['amount' => $amount, 
                'due_date' => $input['invoice_date'],
                'updated_at' => Carbon::now('Asia/Beirut')]);            
        });
    }




    /*----------------------------------
    Update invoice items
    ------------------------------------*/
    public function updateInvoiceItems($id, $invoice_description, $invoice_item_amount)
    {   
        \DB::table('ta_invoice_items')
            ->where('invoice_item_id', $id)
            ->update(['description' =>nl2br($invoice_description), 
            'amount' => $invoice_item_amount, 
            'updated_at' => Carbon::now('Asia/Beirut')
        ]);
    }


    /*----------------------------------
    Hide Invoice by updating hidden attribute to 1
    ------------------------------------*/
    public function hideInvoice($invoice_id)
    {
        \DB::table('ta_invoices')
            ->where('invoice_id', $invoice_id)
            ->update(['hidden' => '1',
            'updated_at' => Carbon::now('Asia/Beirut')]);
    }

    /*----------------------------------
    Get the statuses of all invoices
    ------------------------------------*/
    public function getAllInvoiceStatus()
    {
        $q = \DB::select("SELECT * FROM ta_status ORDER BY name ASC");
        return $q;
    }


    public function updateInvoiceStatus($invoice_id, $status_id)
    {
      \DB::table('ta_invoices')
            ->where('invoice_id', $invoice_id)
            ->update(['status_id' => $status_id,
            'updated_at' => Carbon::now('Asia/Beirut')]);
    }



}