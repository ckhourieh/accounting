<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\DashboardRepository;
use Carbon\Carbon;



class DashboardController extends Controller
{

    private $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
        $this->middleware('auth');
    }

    /*---------------------------------------------------------
    Shows the dashboad and most important KPIs of the accounting system
    -------------------------------------------------------------*/
    public function index()
    {          
        //Get the monthly income per month since webneoo's creation
        $income = $this->dashboardRepository->getMonthlyIncomes();
        //Get the monthly expenses per month since webneoo's creation
        $expenses = $this->dashboardRepository->getMonthlyExpenses();
        //Get the monthly profit per month since webneoo's creation
        $profit = $this->dashboardRepository->getMonthlyProfit();   
        //Get monthly average expenses by categories of expenses
        $avgMonthExpByCateg = $this->dashboardRepository->getAvgMonthlyExpensesByCategory();
        //Get the average expenses of webneoo by Category and by Month
        $avgMonthExp = $this->dashboardRepository->getAvgMonthlyExpensesByCategory();
        //Get Total due payments
        $totDuePayments = $this->dashboardRepository->getTotalDuePayments();
        //Get Due Payments by clients
        $duePaymentsByClients = $this->dashboardRepository->getDuePaymentsByClients();     
        //Get total profit
        $getTotalProfit = $this->dashboardRepository->getTotalProfit();
        //Get total income
        $getTotalIncome = $this->dashboardRepository->getTotalIncome();
        //Get total expenses
        $getTotalExpenses = $this->dashboardRepository->getTotalExpenses();


        echo 'Total Income: '.$getTotalIncome[0]->total_income.'<br>';
        echo 'Total Expenses: '.$getTotalExpenses[0]->total_expenses.'<br>';
        echo 'Total Profit: '.$getTotalProfit[0]->total_profit.'<br>';
        echo 'Total Due Payments: '.$totDuePayments[0]->total_due_payment.'<br>';

    }

}
