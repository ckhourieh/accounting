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
        $q = \DB::select("SELECT A.*, SUM(B.amount) as total_amount_due, SUM(C.amount) as total_income, COUNT(B.invoice_id) as next_payments
                          FROM ta_sources as A 
                          LEFT JOIN ta_invoices as B ON A.source_id = B.client_id AND B.status_id != 3 AND B.status_id != 6
                          LEFT JOIN ta_invoices as C ON A.source_id = C.client_id AND C.status_id = 3 AND C.status_id != 6
                          WHERE A.hidden = '0' AND A.type_id = 1
                          GROUP BY A.source_id");
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
        $q = \DB::select("SELECT A.invoice_id, A.title, A.client_id, A.amount, A.due_date, DATE(A.created_at) as created_at, C.name as status, A.paid, B.name, B.email
                        FROM ta_invoices as A 
                        JOIN ta_sources as B ON A.client_id = B.source_id 
                        JOIN ta_status as C ON A.status_id = C.status_id
                        WHERE A.hidden = '0'");
        return $q;
    }

    /*----------------------------------
    Get all due invoices for a client
    ------------------------------------*/
    public function getDueInvoices($client_id)
    {
        $q = \DB::select("SELECT A.invoice_id, A.title, A.client_id, A.amount, A.due_date, C.name as status, A.paid, B.name, B.email
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
        $q = \DB::select("SELECT A.invoice_id, A.title, A.client_id, A.amount, A.due_date, C.name as status, A.paid, B.name, B.email
                        FROM ta_invoices as A 
                        JOIN ta_sources as B ON A.client_id = B.source_id 
                        JOIN ta_status as C ON A.status_id = C.status_id
                        WHERE A.hidden = '0'
                        AND A.status_id = '3' AND A.status_id != '6'
                        AND A.client_id = :source_id",
            array(':source_id' => $client_id));
        return $q;
    }

    /*----------------------------------
    Get a specific invoice's information
    ------------------------------------*/
    public function getInvoice($invoice_id)
    {
        $q = \DB::select("SELECT A.invoice_id, A.title, A.due_date, DATE(A.created_at) as created_at, A.amount, A.paid, 
                          B.name as client_name, B.email, B.address, B.contact_name, C.name as status_name
                          FROM ta_invoices as A
                          JOIN ta_sources as B ON A.client_id = B.source_id
                          JOIN ta_status as C ON A.status_id = C.status_id
                          WHERE A.hidden = '0' AND A.invoice_id = :invoice_id",
            array(':invoice_id' => $invoice_id));
        return $q;
    }

    /*----------------------------------
    Get Invoice Items
    ------------------------------------*/
    public function getInvoiceItems($invoice_id)
    {
        $q = \DB::select("SELECT B.invoice_item_id, B.title, B.item_amount
                          FROM ta_invoices as A , ta_invoice_items as B
                          WHERE A.hidden = '0' AND A.invoice_id = B.invoice_id AND A.invoice_id = :invoice_id",
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
    Add a new invoice
    ------------------------------------*/
    public function addInvoice($input, $amount)
    {
        \DB::transaction(function() use ($input, $amount) {

            \DB::table('ta_invoices')->insert([
                'title' => $input['invoice_title'], 
                'client_id' => $input['invoice_client'],
                'amount' => $amount, 
                'due_date' => $input['invoice_date'],
                'status_id' => '6',
                'paid' => '0',
                'hidden' => '0',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $i = \DB::select("SELECT invoice_id FROM ta_invoices ORDER BY invoice_id DESC LIMIT 1");

            $q = \DB::select("SELECT source_id, contact_name FROM ta_sources WHERE source_id = :source_id",
                array(':source_id' => $input['invoice_client']));

            \DB::table('ta_transactions')->insert([
                'invoice_id' => $i[0]->invoice_id,
                'source_id' => $q[0]->source_id, 
                'amount' => $amount,
                'contact' => $q[0]->contact_name, 
                'date' => $input['invoice_date'],
                'type' => '1',
                'hidden' => '0',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        });
    }

    /*----------------------------------
    Add invoice items
    ------------------------------------*/
    public function addInvoiceItems($lastInvoiceId, $invoice_item_title, $invoice_item_amount)
    {
        \DB::table('ta_invoice_items')->insert([
            'invoice_id' => $lastInvoiceId,
            'title' => $invoice_item_title, 
            'item_amount' => $invoice_item_amount, 
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /*----------------------------------
    Update all invoice information for a specific invoice
    ------------------------------------*/
    public function updateInvoice($input, $amount)
    {
        \DB::table('ta_invoices')
            ->where('invoice_id', $input['invoice_id'])
            ->update(['title' => $input['invoice_title'], 
            'source_id' => $input['invoice_client'],
            'amount' => $amount, 
            'due_date' => $input['invoice_date'],
            'status' => $input['invoice_status'],
            'paid' => $input['invoice_paid'],
            'updated_at' => date('Y-m-d H:i:s')]);
    }

    /*----------------------------------
    Update invoice items
    ------------------------------------*/
    public function updateInvoiceItems($id, $invoice_item_title, $invoice_item_amount)
    {
        \DB::table('ta_invoice_items')
            ->where('invoice_item_id', $id)
            ->update(['title' => $invoice_item_title, 
            'item_amount' => $invoice_item_amount, 
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /*----------------------------------
    Hide Invoice by updating hidden attribute to 1
    ------------------------------------*/
    public function hideInvoice($invoice_id)
    {
        \DB::table('ta_invoices')
            ->where('id', $invoice_id)
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
        $q = \DB::select("SELECT A.*, B.name as source_name
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
        $q = \DB::select("SELECT * FROM ta_transactions WHERE transaction_id = :transaction_id",
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