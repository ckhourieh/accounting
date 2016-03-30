<?php namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class HomeRepository {

    /* **************************
                CLIENTS
     ************************** */

    /*----------------------------------
    Get all clients and their information
    ------------------------------------*/
    public function getClients()
    {
        $q = \DB::select("SELECT T.*, SUM(T.total_amount_due) as total_amount_due, SUM(T.total_income) as total_income, SUM(T.next_payments) as next_payments
                          FROM (SELECT A.*, NULL as total_amount_due, NULL as total_income, COUNT(D.invoice_id) as next_payments
                                FROM ta_sources as A
                                LEFT JOIN ta_invoices as D ON A.source_id = D.client_id AND D.next_payment > CURDATE()
                                WHERE A.hidden = '0' AND A.type_id = 1
                                GROUP BY A.source_id
                                UNION
                                SELECT A.*, SUM(B.amount) as total_amount_due, NULL as total_income, NULL as next_payments
                                FROM ta_sources as A
                                LEFT JOIN ta_invoices as B ON A.source_id = B.client_id AND B.status_id != 3 AND B.status_id != 6
                                WHERE A.hidden = '0' AND A.type_id = 1
                                GROUP BY A.source_id
                                UNION
                                SELECT A.*, NULL as total_amount_due, SUM(C.paid) as total_income, NULL as next_payments
                                FROM ta_sources as A
                                LEFT JOIN ta_invoices as C ON A.source_id = C.client_id
                                WHERE A.hidden = '0' AND A.type_id = 1
                                GROUP BY A.source_id) as T
                          GROUP BY T.source_id");
        return $q;
    }

    public function getAllStatus()
    {
        $q = \DB::select("SELECT * FROM ta_status ORDER BY name ASC");
        return $q;
    }

    /*----------------------------------
    Get all client ids and names
    ------------------------------------*/
    public function getClientNames()
    {
        $q = \DB::select("SELECT source_id, name FROM ta_sources WHERE hidden = '0' AND type_id = 1 ORDER BY name ASC");
        return $q;
    }

    /*----------------------------------
    Get a specific client or supplier information
    ------------------------------------*/
    public function getSource($client_id)
    {
        $q = \DB::select("SELECT * FROM ta_sources WHERE source_id = :source_id",
            array(':source_id' => $client_id));
        return $q;
    }

    /*----------------------------------
    Add a new client
    ------------------------------------*/
    public function addClient($input)
    {
        \DB::table('ta_sources')->insert([
            'name' => $input['client_name'],
            'desc' => $input['client_desc'], 
            'email' => $input['client_email'],
            'phone' => $input['client_phone'], 
            'address' => $input['client_address'], 
            'owner' => $input['client_owner'],
            'contact_name' => $input['client_contact'],
            'accounting_id' => $input['client_accounting'],
            'hidden' => '0',
            'type_id' => '1',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /*----------------------------------
    Update all source information for a specific source
    ------------------------------------*/
    public function updateSource($input)
    {
        \DB::table('ta_sources')
            ->where('source_id', $input['source_id'])
            ->update(['name' => $input['source_name'],
            'desc' => $input['source_desc'], 
            'email' => $input['source_email'],
            'phone' => $input['source_phone'], 
            'address' => $input['source_address'], 
            'owner' => $input['source_owner'],
            'contact_name' => $input['source_contact'],
            'accounting_id' => $input['source_accounting'],
            'updated_at' => date('Y-m-d H:i:s')]);
    }

    /*----------------------------------
    Hide source by updating hidden attribute to 1
    ------------------------------------*/
    public function hideSource($source_id)
    {
        \DB::table('ta_sources')
            ->where('source_id', $source_id)
            ->update(['hidden' => '1',
            'updated_at' => date('Y-m-d H:i:s')]);
    }


    /* **************************
              SUPPLIERS
     ************************** */

    /*----------------------------------
    Get all suppliers and their information
    ------------------------------------*/
    public function getSuppliers()
    {
        $q = \DB::select("SELECT A.*, SUM(B.amount) as total_amount_spent
                          FROM ta_sources as A 
                          LEFT JOIN ta_transactions as B ON A.source_id = B.source_id AND B.type = 0
                          WHERE A.hidden = '0' AND A.type_id = 2
                          GROUP BY A.source_id");
        return $q;
    }

    /*----------------------------------
    Get all supplier ids and names
    ------------------------------------*/
    public function getSupplierNames()
    {
        $q = \DB::select("SELECT source_id, name FROM ta_sources WHERE hidden = '0' AND type_id = 2 ORDER BY name ASC");
        return $q;
    }

    /*----------------------------------
    Add a new supplier
    ------------------------------------*/
    public function addSupplier($input)
    {
        \DB::table('ta_sources')->insert([
            'name' => $input['supplier_name'],
            'desc' => $input['supplier_desc'], 
            'email' => $input['supplier_email'],
            'phone' => $input['supplier_phone'], 
            'address' => $input['supplier_address'], 
            'owner' => $input['supplier_owner'],
            'contact_name' => $input['supplier_contact'],
            'accounting_id' => $input['supplier_accounting'],
            'hidden' => '0',
            'type_id' => '2',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /* **************************
               INVOICES
     ************************** */

    /*----------------------------------
    Get all invoices and their information
    ------------------------------------*/
    public function getInvoices()
    {
        \DB::transaction(function() {
            //Select all invoices where due date is passed and status is not paid
            $invoices = \DB::select("SELECT * FROM ta_invoices WHERE due_date < CURDATE() 
                              AND ( (status_id = 1) OR (status_id = 2) OR (status_id = 5) )");
            
            //Set the status of all these invoices to Overdue
            foreach($invoices as $i)
            {
                \DB::table('ta_invoices')
                ->where('invoice_id', $i->invoice_id)
                ->update(['status_id' => '4',
                'updated_at' => date('Y-m-d H:i:s')]);
            }
        });

        \DB::transaction(function() {
            //Select all invoices where next payment date is passed
            $invoices = \DB::select("SELECT * FROM ta_invoices WHERE next_payment <= CURDATE()");
           
            foreach ($invoices as $i) {
                \DB::table('ta_invoices')->insert([
                    'client_id' => $i->client_id,
                    'amount' => $i->amount, 
                    'status_id' => '6',
                    'paid' => '0',
                    'hidden' => '0',
                    'created_at' => date('Y-m-d H:i:s')
                ]);


                \DB::table('ta_invoices')
                ->where('invoice_id', $i->invoice_id)
                ->update(['next_payment' => NULL,
                'updated_at' => date('Y-m-d H:i:s')]);
            }
        });

        $q = \DB::select("SELECT A.invoice_id, A.client_id, A.amount, A.due_date, A.next_payment, DATE(A.created_at) as created_at, A.status_id, C.name as status, 
                            A.paid, B.name, B.email
                            FROM ta_invoices as A 
                            JOIN ta_sources as B ON A.client_id = B.source_id 
                            JOIN ta_status as C ON A.status_id = C.status_id
                            WHERE A.hidden = '0'");

        return $q;
    }


    /*----------------------------------
    Get all Social Media invoices not yet paid
    ------------------------------------*/
    public function getServiceDueInvoices($service_id)
    {
        $q = \DB::select("SELECT SUM(A.amount) as total, C.title as service_name, A.invoice_id
                          FROM ta_invoices as A 
                          JOIN ta_invoice_items as B ON A.invoice_id = B.invoice_id 
                          JOIN ta_service_types as C ON B.service_type_id = C.service_type_id
                          WHERE A.hidden = '0'
                          AND A.status_id != '3' AND A.status_id != '6'
                          AND B.service_type_id = :service_id",
            array(':service_id' => $service_id));
        return $q;
    }

    /*----------------------------------
    Get all due invoices for a client
    ------------------------------------*/
    public function getDueInvoices($client_id)
    {
        $q = \DB::select("SELECT A.invoice_id, A.client_id, A.amount, A.due_date, DATE(A.created_at) as created_at, A.next_payment, C.name as status, A.paid, 
                        B.name, B.email
                        FROM ta_invoices as A 
                        JOIN ta_sources as B ON A.client_id = B.source_id 
                        JOIN ta_status as C ON A.status_id = C.status_id
                        WHERE A.hidden = '0'
                        AND A.status_id != '3' AND A.status_id != '6'
                        AND A.client_id = :source_id",
            array(':source_id' => $client_id));
        return $q;
    }

    /*----------------------------------
    Get all payed invoices for a client
    ------------------------------------*/
    public function getPayedInvoices($client_id)
    {
        $q = \DB::select("SELECT A.invoice_id, A.client_id, A.amount, A.due_date, DATE(A.created_at) as created_at, A.next_payment, C.name as status, A.paid, 
                        B.name, B.email
                        FROM ta_invoices as A 
                        JOIN ta_sources as B ON A.client_id = B.source_id 
                        JOIN ta_status as C ON A.status_id = C.status_id
                        WHERE A.hidden = '0'
                        AND A.paid != '0'
                        AND A.client_id = :source_id",
            array(':source_id' => $client_id));
        return $q;
    }

    /*----------------------------------
    Get a specific invoice's information
    ------------------------------------*/
    public function getInvoice($invoice_id)
    {
        $q = \DB::select("SELECT A.invoice_id, A.due_date, DATE(A.created_at) as created_at, A.amount, A.paid, A.client_id,
                          A.status_id, B.accounting_id, B.name as client_name, B.email, B.address, B.contact_name, B.phone
                          FROM ta_invoices as A
                          JOIN ta_sources as B ON A.client_id = B.source_id
                          WHERE A.hidden = '0' AND A.invoice_id = :invoice_id",
            array(':invoice_id' => $invoice_id));
        return $q;
    }

    /*----------------------------------
    Get Invoice Services
    ------------------------------------*/
    public function getInvoiceServices($invoice_id)
    {
        $q = \DB::select("SELECT DISTINCT C.service_type_id, C.title as service_title
                          FROM ta_invoices as A
                          JOIN ta_invoice_items as B ON A.invoice_id = B.invoice_id
                          JOIN ta_service_types as C ON B.service_type_id = C.service_type_id
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
    public function addInvoice($input, $amount)
    {
        if ($input['invoice_next_payment'] == "")
            $input['invoice_next_payment'] = NULL;

        \DB::transaction(function() use ($input, $amount) {

            \DB::table('ta_invoices')->insert([
                'client_id' => $input['invoice_client'],
                'amount' => $amount, 
                'due_date' => $input['invoice_date'],
                'next_payment' => $input['invoice_next_payment'],
                'status_id' => '6',
                'paid' => '0',
                'hidden' => '0',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            
        });
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
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }



    /*----------------------------------
    Update all invoice information for a specific invoice
    ------------------------------------*/
    public function updateInvoice($input, $amount)
    {
        \DB::transaction(function() use ($input, $amount) {
            //Update the invoice data
            \DB::table('ta_invoices')
                ->where('invoice_id', $input['invoice_id'])
                ->update(['client_id' => $input['invoice_client'],
                'amount' => $amount, 
                'due_date' => $input['invoice_date'],
                'status_id' => $input['invoice_status'],
                'paid' => $input['invoice_paid'],
                'updated_at' => date('Y-m-d H:i:s')]);
  
            // If the status of the invoice is paid or incomplete we need to insert a new transaction.
            if($input['invoice_status'] == 3 OR $input['invoice_status'] == 2)
            {
                $q = \DB::select("SELECT source_id, contact_name FROM ta_sources WHERE source_id = :source_id",
                array(':source_id' => $input['invoice_client']));

                \DB::table('ta_transactions')->insert([
                    'invoice_id' => $input['invoice_id'],
                    'source_id' => $q[0]->source_id, 
                    'amount' => $amount,
                    'date' => $input['invoice_date'],
                    'type' => '1',
                    'hidden' => '0',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        });
    }



    /*----------------------------------
    Update invoice items
    ------------------------------------*/
    public function updateInvoiceItems($id, $invoice_description, $invoice_item_amount)
    {   
        \DB::table('ta_invoice_items')
            ->where('invoice_item_id', $id)
            ->update(['description' =>$invoice_description, 
            'amount' => $invoice_item_amount, 
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /*----------------------------------
    Update invoice items
    ------------------------------------*/
    public function updateInvoiceStatusToSent($invoice_id)
    {
        \DB::table('ta_invoices')
            ->where('invoice_id', $invoice_id)
            ->update(['status_id' =>'1',
            'updated_at' => date('Y-m-d H:i:s')
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
            'updated_at' => date('Y-m-d H:i:s')]);
    }


    /* **************************
            TRANSACTIONS
     ************************** */

    /*----------------------------------
    Get all transactions and their information
    ------------------------------------*/
    public function getTransactions()
    {
        $q = \DB::select("SELECT A.*, B.name as source_name, B.contact_name
                          FROM ta_transactions as A 
                          JOIN ta_sources as B ON A.source_id = B.source_id
                          WHERE A.hidden = '0' 
                          ORDER BY A.transaction_id ASC");
        return $q;
    }

    /*----------------------------------
    Get a specific transaction's information
    ------------------------------------*/
    public function getTransaction($transaction_id)
    {
        $q = \DB::select("SELECT A.*, B.name as source_name, B.contact_name
                          FROM ta_transactions as A
                          JOIN ta_sources as B ON A.source_id = B.source_id
                          WHERE transaction_id = :transaction_id",
            array(':transaction_id' => $transaction_id));
        return $q;
    }

    /*----------------------------------
    Get all spent transactions on a supplier
    ------------------------------------*/
    public function getSpentTransactions($supplier_id)
    {
        $q = \DB::select("SELECT A.transaction_id, A.source_id, B.name as supplier_name, A.amount, A.date, B.contact_name
                        FROM ta_transactions as A 
                        JOIN ta_sources as B ON A.source_id = B.source_id 
                        WHERE A.hidden = 0
                        AND A.type = 0
                        AND A.source_id = :supplier_id",
            array(':supplier_id' => $supplier_id));
        return $q;
    }

    /*----------------------------------
    Add a new transaction
    ------------------------------------*/
    public function addTransaction($input)
    {
        \DB::table('ta_transactions')->insert([
            'invoice_id' => $input['transaction_invoice'],
            'source' => $input['transaction_source'], 
            'amount' => $input['transaction_amount'],
            'contact' => $input['transaction_contact'], 
            'date' => $input['transaction_date'],
            'type' => $input['transaction_type'],
            'hidden' => '0',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /*----------------------------------
    Update all transaction information for a specific transaction
    ------------------------------------*/
    public function updateTransaction($input)
    {
        \DB::table('ta_transactions')
            ->where('transaction_id', $input['transaction_id'])
            ->update(['invoice_id' => $input['transaction_invoice'],
            'source' => $input['transaction_source'], 
            'amount' => $input['transaction_amount'],
            'contact' => $input['transaction_contact'], 
            'date' => $input['transaction_date'],
            'type' => $input['transaction_type'],
            'updated_at' => date('Y-m-d H:i:s')]);
    }

    /*----------------------------------
    Hide Transaction by updating hidden attribute to 1
    ------------------------------------*/
    public function hideTransaction($transaction_id)
    {
        \DB::table('ta_transactions')
            ->where('transaction_id', $transaction_id)
            ->update(['hidden' => '1',
            'updated_at' => date('Y-m-d H:i:s')]);
    }

}