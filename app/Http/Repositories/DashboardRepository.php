<?php namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/*---------------------------------------------------------
    TRY USING THE TRANSACTIONS AND INVOICE TABLES TO WORK ON KPIs
-------------------------------------------------------------*/

class DashboardRepository {

    /*---------------------------------------------------------
    Get all the monthly incomes of webneoo's since its creation
    -------------------------------------------------------------*/
    public function getMonthlyIncomes()
    {
        $q = \DB::select("SELECT  YEAR(date) as this_year, MONTH(date) as this_month, SUM(amount) as total_income
                          FROM ta_transactions
                          WHERE type = 1
                          AND hidden = 0
                          GROUP BY YEAR(date), MONTH(date)");
        return $q;
    }


    /*---------------------------------------------------------
    Get total income
    -------------------------------------------------------------*/
    public function getTotalIncome()
    {
        $q = \DB::select("SELECT SUM(amount) as total_income
                          FROM ta_transactions
                          WHERE type = 1
                          AND hidden = 0");
        return $q;
    }

    /*---------------------------------------------------------
    Get all the monthly expenses of webneoo's since its creation
    -------------------------------------------------------------*/
    public function getMonthlyExpenses()
    {
        $q = \DB::select("SELECT  YEAR(date) as this_year, MONTH(date) as this_month, SUM(amount) as total_expenses
                          FROM ta_transactions
                          WHERE type = 0
                          AND hidden = 0
                          GROUP BY YEAR(date), MONTH(date)");
        return $q;
    }

    /*---------------------------------------------------------
    Get all the profit of Webneoo by Month
    -------------------------------------------------------------*/
    public function getMonthlyProfit()
    {
        $q = \DB::select("SELECT  YEAR(date) as this_year, MONTH(date) as this_month, SUM(amount) as total_profit
                          FROM ta_transactions
                          WHERE hidden = 0
                          GROUP BY YEAR(date), MONTH(date)");
        return $q;
    }

    /*----------------
    Get total profit
    ------------------*/
    public function getTotalProfit()
    {
        $q = \DB::select("SELECT SUM(amount) as total_profit
                          FROM ta_transactions
                          WHERE hidden = 0");
        return $q;
    }

    /*---------------------------------------------------------
    Get the average expenses of webneoo by Category and by Month
    -------------------------------------------------------------*/
    public function getAvgMonthlyExpensesByCategory()
    {
        $q = \DB::select("SELECT SUM(A.amount)/TIMESTAMPDIFF(MONTH,
                                                (SELECT date FROM ta_transactions ORDER BY date ASC LIMIT 1),
                                                (NOW())) as avg_monthly_expenses, 
                                   B.name
                           FROM ta_transactions as A
                           JOIN ta_categories as B ON A.category_id = B.category_id
                           WHERE type = 0
                           AND hidden = 0
                           GROUP BY B.category_id;");
        return $q;
    }

    /*---------------------------------------------------------
    Get total expenses
    -------------------------------------------------------------*/
    public function getTotalExpenses()
    {
        $q = \DB::select("SELECT SUM(amount) as total_expenses
                          FROM ta_transactions
                          WHERE type = 0
                          AND hidden = 0");
        return $q;
    }


    /*--------------------------------
    Get the average expenses by month
    --------------------------------- */
    public function getAvgMonthlyExpenses()
    {
        $q = \DB::select("SELECT SUM(amount)/TIMESTAMPDIFF(MONTH,
                                                (SELECT date FROM ta_transactions ORDER BY date ASC LIMIT 1),
                                                (NOW())) as avg_monthly_expenses
                          FROM ta_transactions
                          WHERE type = 0
                          AND hidden = 0");
        return $q;
    }

    /*--------------------------------
    Get the total due payments
    --------------------------------- */
    public function getTotalDuePayments()
    {
        $q = \DB::select("SELECT SUM(C.total_due_amount) as total_due_payment
                          FROM
                            (SELECT A.*, B.sum_transactions, (A.sum_invoices - B.sum_transactions) as total_due_amount
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
                                
                            ON A.source_id = B.source_id ORDER BY A.source_id ASC) as C");
        return $q;
    }

    /*--------------------------------
    Get the due payments classified by clients
    --------------------------------- */
    public function getDuePaymentsByClients()
    {

        $q = \DB::select("SELECT A.*, B.sum_transactions, (A.sum_invoices - B.sum_transactions) as total_due_amount
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

}