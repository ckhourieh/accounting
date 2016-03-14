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
      $q = \DB::select("SELECT * FROM ta_clients WHERE hidden = '0' ORDER BY client_id ASC");
      return $q;
    }

    /*----------------------------------
    Get all client ids and names
    ------------------------------------*/
    public function getClientNames()
    {
      $q = \DB::select("SELECT client_id, name FROM ta_clients WHERE hidden = '0' ORDER BY name ASC");
      return $q;
    }

    /*----------------------------------
    Get a specific client's information
    ------------------------------------*/
    public function getClient($client_id)
    {
      $q = \DB::select("SELECT * FROM ta_clients WHERE client_id = :client_id",
            array(':client_id' => $client_id));
      return $q;
    }

    /*----------------------------------
    Add a new client
    ------------------------------------*/
    public function addClient($input)
    {
      \DB::table('ta_clients')->insert([
            'name' => $input['client_name'],
            'desc' => $input['client_desc'], 
            'email' => $input['client_email'],
            'phone' => $input['client_phone'], 
            'address' => $input['client_address'], 
            'owner' => $input['client_owner'],
            'contact_name' => $input['client_contact'],
            'accounting_id' => $input['client_accounting'],
            'hidden' => '0',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /*----------------------------------
    Update all client information for a specific client
    ------------------------------------*/
    public function updateClient($input)
    {
      \DB::table('ta_clients')
            ->where('client_id', $input['client_id'])
            ->update(['name' => $input['client_name'],
            'desc' => $input['client_desc'], 
            'email' => $input['client_email'],
            'phone' => $input['client_phone'], 
            'address' => $input['client_address'], 
            'owner' => $input['client_owner'],
            'contact_name' => $input['client_contact'],
            'accounting_id' => $input['client_accounting'],
            'updated_at' => date('Y-m-d H:i:s')]);
    }

    /*----------------------------------
    Hide Client by updating hidden attribute to 1
    ------------------------------------*/
    public function hideClient($client_id)
    {
      \DB::table('ta_clients')
            ->where('client_id', $client_id)
            ->update(['hidden' => '1',
            'updated_at' => date('Y-m-d H:i:s')]);
    }


    /* **************************
               INVOICES
     ************************** */

    /*----------------------------------
    Get all invoices and their information
    ------------------------------------*/
    public function getInvoices()
    {
      $q = \DB::select("SELECT A.id, A.invoice_id, A.title, A.client_id, A.amount, A.due_date, A.status, A.paid, B.name, B.email, C.frequency
                        FROM ta_invoices as A LEFT JOIN ta_clients as B ON A.client_id = B.client_id LEFT JOIN ta_frequencies as C ON A.frequency_id = C.frequency_id
                        WHERE A.hidden = '0'");
      return $q;
    }

    /*----------------------------------
    Get a specific invoice's information
    ------------------------------------*/
    public function getInvoice($invoice_id)
    {
      $q = \DB::select("SELECT A.invoice_id, A.due_date, A.created_at, A.amount, B.name, B.email, B.address, B.contact_name
                        FROM ta_invoices as A , ta_clients as B
                        WHERE A.hidden = '0' AND A.client_id = B.client_id AND A.invoice_id = :invoice_id",
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
    Add a new invoice
    ------------------------------------*/
    public function addInvoice($input, $amount)
    {
        \DB::transaction(function() use ($input, $amount) {

            \DB::table('ta_invoices')->insert([
                'invoice_id' => $input['invoice_id'],
                'title' => $input['invoice_title'], 
                'client_id' => $input['invoice_client'],
                'amount' => $amount, 
                'due_date' => $input['invoice_date'],
                'frequency_id' => $input['invoice_frequency'],
                'status' => '0',
                'paid' => '0',
                'hidden' => '0',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $q = \DB::select("SELECT name, contact_name FROM ta_clients WHERE client_id = :client_id",
                array(':client_id' => $input['invoice_client']));

            \DB::table('ta_transactions')->insert([
                'invoice_id' => $input['invoice_id'],
                'source' => $q[0]->name, 
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
    public function addInvoiceItems($invoice_id, $invoice_item_title, $invoice_item_amount)
    {
      \DB::table('ta_invoice_items')->insert([
            'invoice_id' => $invoice_id,
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
            ->where('id', $input['id'])
            ->update(['invoice_id' => $input['invoice_id'],
            'title' => $input['invoice_title'], 
            'client_id' => $input['invoice_client'],
            'amount' => $amount, 
            'due_date' => $input['invoice_date'],
            'frequency_id' => $input['invoice_frequency'],
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


    /*----------------------------------
    Get all frequencies
    ------------------------------------*/
    public function getFrequencies()
    {
      $q = \DB::select("SELECT * FROM ta_frequencies ORDER BY frequency ASC");
      return $q;
    }


    /* **************************
                TRANSACTIONS
     ************************** */

    /*----------------------------------
    Get all transactions and their information
    ------------------------------------*/
    public function getTransactions()
    {
      $q = \DB::select("SELECT * FROM ta_transactions WHERE hidden = '0' ORDER BY transaction_id ASC");
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