<?php namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SupplierRepository {

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
    Get Webneoo's suppliers list with its main information
    --------------------------------------------------*/
    public function getAll()
    {
        $q = \DB::select("SELECT A.*, B.total_expenses FROM ta_sources as A
                            LEFT JOIN
                            (SELECT source_id, hidden, SUM(amount) as total_expenses FROM ta_transactions WHERE type = 0 AND hidden = 0 GROUP BY source_id) as B
                            ON A.source_id = B.source_id AND B.hidden = 0
                            WHERE type_id = 2
                            AND A.hidden = 0");
        return $q;
    }


    /*----------------------------------
    Add a new supplier
    ------------------------------------*/
    public function add($input, $filename)
    {
        //Insert a new supplier in the system
        \DB::table('ta_sources')->insert([
            'name' => $input['supplier_name'],
            'desc' => $input['supplier_desc'], 
            'email' => $input['supplier_email'],
            'phone' => $input['supplier_phone'],
            'img' => $filename, 
            'address' => $input['supplier_address'], 
            'owner' => $input['supplier_owner'],
            'contact_name' => $input['supplier_contact'],
            'accounting_id' => $input['supplier_accounting'],
            'hidden' => '0',
            'type_id' => '2',
            'created_at' => Carbon::now('Asia/Beirut'),
            'updated_at' => Carbon::now('Asia/Beirut')
        ]);
    }


    /*----------------------------------
    Get all spent transactions on a supplier
    ------------------------------------*/
    public function getExpenses($supplier_id)
    {
        $q = \DB::select("SELECT A.*, B.name as supplier_name, B.contact_name
                        FROM ta_transactions as A 
                        JOIN ta_sources as B ON A.source_id = B.source_id 
                        WHERE A.hidden = 0
                        AND A.type = 0
                        AND A.source_id = :supplier_id",
            array(':supplier_id' => $supplier_id));
        return $q;
    }


}