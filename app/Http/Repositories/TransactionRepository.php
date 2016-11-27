<?php namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionRepository {

  /*----------------------------------
    Get only accountant transactions and their information
    ------------------------------------*/
    public function getTransactions()
    {
        $q = \DB::select("SELECT A.*, B.name as source_name, B.type_id, B.source_id, C.invoice_nb
                          FROM ta_transactions as A 
                          LEFT JOIN ta_sources as B ON A.source_id = B.source_id AND B.hidden = 0
                          LEFT JOIN ta_invoices as C ON A.invoice_id = C.invoice_id AND C.hidden = 0
                          WHERE A.hidden = '0' 
                          AND salary_id IS NULL
                          ORDER BY A.transaction_id ASC");
        return $q;
    }

    /*----------------------------------
    Get all transactions and their information
    ------------------------------------*/
    public function getAllTransactions()
    {
        $q = \DB::select("SELECT A.*, B.name as source_name, B.type_id, B.source_id, C.invoice_nb
                          FROM ta_transactions as A 
                          LEFT JOIN ta_sources as B ON A.source_id = B.source_id AND B.hidden = 0
                          LEFT JOIN ta_invoices as C ON A.invoice_id = C.invoice_id AND C.hidden = 0
                          WHERE A.hidden = '0' 
                          ORDER BY A.transaction_id ASC");
        return $q;
    }

 
    /*----------------------------------
    Get a specific transaction's information
    ------------------------------------*/
    public function getTransaction($transaction_id)
    {
        $q = \DB::select("SELECT A.*, B.name as source_name, B.contact_name, B.img, B.type_id, C.invoice_nb
                          FROM ta_transactions as A
                          LEFT JOIN ta_sources as B ON A.source_id = B.source_id AND B.hidden = 0
                          LEFT JOIN ta_invoices as C ON A.invoice_id = C.invoice_id AND C.hidden = 0
                          WHERE transaction_id = :transaction_id",
            array(':transaction_id' => $transaction_id));
        return $q;
    }

    /*----------------------------------
    Get a specific transactions from invoice id
    ------------------------------------*/
    public function getTransactionFromInvoiceId($invoice_id)
    {
        $q = \DB::select("SELECT *
                          FROM ta_transactions 
                          WHERE invoice_id = :invoice_id
                          AND hidden = 0",
            array(':invoice_id' => $invoice_id));
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
    Get the list of all the categories
    ------------------------------------*/
    public function getAllCategories()
    {
        $q = \DB::select("SELECT *
                          FROM ta_categories
                          ORDER BY name ASC");
        return $q;
    }


    /*---------------------------------------------
    //Add an income transaction to the system
    ------------------------------------------------*/
    public function addTransaction($invoice_id, $source_id, $amount, $description, $category_id, $date, $type){

            if($invoice_id == "") $invoice_id = null;

            //Add the transaction
            \DB::table('ta_transactions')->insert([
                'invoice_id' => $invoice_id,
                'source_id' => $source_id, 
                'amount' => $amount,
                'description' => $description,
                'category_id' => $category_id,
                'date' => $date,
                'type' => $type,
                'hidden' => '0',
                'created_at' => Carbon::now('Asia/Beirut'),
                'updated_at' => Carbon::now('Asia/Beirut')
            ]);

        
    }

    

    /*----------------------------------
    Hide Transaction by updating hidden attribute to 1
    ------------------------------------*/
    public function hideTransaction($transaction_id)
    {
        \DB::table('ta_transactions')
            ->where('transaction_id', $transaction_id)
            ->update(['hidden' => '1',
            'updated_at' => Carbon::now('Asia/Beirut')]);
    }



    /*----------------------------------
    Get all the incomes from all the clients
    ------------------------------------*/
    public function getIncomes()
    {
        $q = \DB::select("SELECT  YEAR(created_at) as this_year, MONTH(created_at) as this_month, SUM(amount) as total_income
                          FROM ta_transactions
                          WHERE YEAR(created_at) = YEAR(CURDATE())
                          AND type = 1
                          GROUP BY YEAR(created_at), MONTH(created_at)");
        return $q;
    }


    


    /*----------------------------------
    Get all the expenses 
    ------------------------------------*/
    public function getExpenses()
    {
        $q = \DB::select("SELECT  MONTH(created_at) as this_month, SUM(amount) as total_expenses
                          FROM ta_transactions
                          WHERE YEAR(created_at) = YEAR(CURDATE())
                          AND type = 0
                          GROUP BY this_month");
        return $q;
    }
}