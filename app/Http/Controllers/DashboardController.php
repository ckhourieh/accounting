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
        $data['income'] = $this->dashboardRepository->getMonthlyIncomes();
        //Get the monthly expenses per month since webneoo's creation
        $data['expenses'] = $this->dashboardRepository->getMonthlyExpenses();
        //Get the monthly profit per month since webneoo's creation
        $data['profit'] = $this->dashboardRepository->getMonthlyProfit();   
        //Get monthly average expenses by categories of expenses
        $data['avg_month_exp_by_categ'] = $this->dashboardRepository->getAvgMonthlyExpensesByCategory();
        //Get the average expenses of webneoo by Category and by Month
        $data['avg_month_exp'] = $this->dashboardRepository->getAvgMonthlyExpenses();
        //Get Total due payments
        $data['total_due_payments'] = $this->dashboardRepository->getTotalDuePayments();
        //Get Due Payments by clients
        $data['due_payments_by_clients'] = $this->dashboardRepository->getDuePaymentsByClients();     
        //Get total profit
        $data['total_profit'] = $this->dashboardRepository->getTotalProfit();
        //Get total income
        $data['total_income'] = $this->dashboardRepository->getTotalIncome();
        //Get total expenses
        $data['total_expenses'] = $this->dashboardRepository->getTotalExpenses();

//dd($data);
        

        return view('dashboard', array('actual_year' => Carbon::Now('Asia/Beirut'), 'data' => $data));

    }

}
