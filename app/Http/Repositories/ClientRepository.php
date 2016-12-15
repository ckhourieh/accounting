<?php namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClientRepository {

    /*-------------------------------------------------
    Get the information of a specific client or supplier 
    -----------------------------------------------------*/
    public function getInfo($client_id)
    {
        $q = \DB::select("SELECT * FROM ta_sources WHERE source_id = :source_id",
            array(':source_id' => $client_id));
        return $q;
    }

    /*----------------------------------
    Update all source information for a specific source
    ------------------------------------*/
    public function update($input, $filename)
    {
        \DB::table('ta_sources')
            ->where('source_id', $input['source_id'])
            ->update(['name' => $input['source_name'],
            'desc' => $input['source_desc'], 
            'email' => $input['source_email'],
            'phone' => $input['source_phone'],
            'img' => $filename,
            'address' => $input['source_address'], 
            'owner' => $input['source_owner'],
            'contact_name' => $input['source_contact'],
            'accounting_id' => $input['source_accounting'],
            'follow_up_date' => $input['follow_up_date'],
            'notes' => $input['notes'],
            'updated_at' => Carbon::now('Asia/Beirut')]);
    }


    /*----------------------------------
    Hide source by updating hidden attribute to 1
    ------------------------------------*/
    public function hide($source_id)
    {
        \DB::table('ta_sources')
            ->where('source_id', $source_id)
            ->update(['hidden' => '1',
            'updated_at' => Carbon::now('Asia/Beirut')]);
    }


   /*-------------------------------------------------
    Get Webneoo's client list with its main information
    --------------------------------------------------*/
    public function getAll()
    {
        $q = \DB::select("SELECT A.*, IFNULL(B.sum_transactions, 0) as sum_transactions, (A.sum_invoices - IFNULL(B.sum_transactions, 0)) as total_due_amount
                          FROM (SELECT A.*, SUM(B.amount) as sum_invoices
                               FROM ta_sources as A
                               LEFT JOIN ta_invoices as B ON A.source_id = B.client_id AND B.status_id != 5 AND B.status_id != 6 AND B.hidden=0
                               WHERE A.type_id = 1
                               AND A.hidden = 0
                               GROUP BY A.source_id) as A
                                 
                           LEFT JOIN

                                (SELECT A.source_id, SUM(A.amount) as sum_transactions
                                FROM ta_transactions as A
                                WHERE A.type = 1
                                AND A.hidden = 0
                                GROUP BY A.source_id) as B 
                                
                            ON A.source_id = B.source_id ORDER BY A.source_id ASC");                  
        return $q;
    }


    /*----------------------------------------------------------------
    Get all invoices related to a specific client with the invoice info
    ------------------------------------------------------------------*/
    public function getInvoices($client_id)
    {
        $q = \DB::select("SELECT A.*, IFNULL(B.paid,0) as paid, (A.amount - IFNULL(B.paid,0)) as remaining
                          FROM
                            (SELECT A.invoice_id, A.invoice_nb, A.client_id, A.amount, A.due_date, DATE(A.created_at) as created_at, C.name as status, 
                            B.name, B.email
                            FROM ta_invoices as A 
                            JOIN ta_sources as B ON A.client_id = B.source_id AND B.hidden = 0
                            JOIN ta_status as C ON A.status_id = C.status_id
                            WHERE A.hidden = '0'
                            AND A.client_id = :source_id) as A

                           LEFT JOIN

                            (SELECT A.invoice_id, SUM(A.amount) as paid
                            FROM ta_transactions as A
                            WHERE A.type = 1
                            AND A.source_id = :source_id2
                            AND A.hidden = 0
                            GROUP BY A.invoice_id) as B

                            ON A.invoice_id = B.invoice_id",
            array(':source_id' => $client_id, ':source_id2' => $client_id));
        return $q;
    }


    /*----------------------------------
    Add a new client
    ------------------------------------*/
    public function add($input, $filename)
    {  
        \DB::table('ta_sources')->insert([
            'name' => $input['client_name'],
            'desc' => $input['client_desc'], 
            'email' => $input['client_email'],
            'phone' => $input['client_phone'], 
            'img' => $filename,
            'address' => $input['client_address'], 
            'owner' => $input['client_owner'],
            'contact_name' => $input['client_contact'],
            'accounting_id' => $input['client_accounting'],
            'hidden' => '0',
            'type_id' => '1',
            'created_at' => Carbon::now('Asia/Beirut'),
            'updated_at' => Carbon::now('Asia/Beirut')
        ]);
    }



}