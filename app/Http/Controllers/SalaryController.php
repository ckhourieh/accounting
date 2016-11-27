<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\TeamRepository;
use App\Http\Repositories\TransportationRepository;
use App\Http\Repositories\SalariesRepository;
use App\Http\Repositories\TransactionRepository;



class SalaryController extends Controller
{

    private $teamRepository;
    private $transportationRepository;
    private $salariesRepository;
    private $transactionRepository;

    public function __construct(TeamRepository $teamRepository, TransportationRepository $transportationRepository, 
        SalariesRepository $salariesRepository, TransactionRepository $transactionRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->transportationRepository = $transportationRepository;
        $this->salariesRepository = $salariesRepository;
        $this->transactionRepository = $transactionRepository;
        $this->middleware('auth');
    }

    /*------------------------------------
    Screen allowing user to configure the preview of the salary
    ------------------------------------- */
    public function generate($user_id)
    {
        
        // get the info of a specific user
        $user_info = $this->teamRepository->getUserInfo($user_id);

        $actual_month = date('m');
        $actual_year = date('Y');
                     
        return view('team.salary', array('user_info' => $user_info,'selected_month' => $actual_month, 'selected_year' => $actual_year));
    }


    /*------------------------------------
    Calculating the previewed salary
    ------------------------------------- */
    public function preview(Request $request, $user_id)
    {   

        // get the info of a specific user
        $user_info = $this->teamRepository->getUserInfo($user_id);


        // calculating the amount to reduce from the days off
        if(isset($request->days_off) || $request->days_off != 0)
        $days_off_amount = ($user_info[0]->base_salary / 20.0)* $request->days_off;
        else
        $days_off_amount = 0;

        $bonus_amount = (float)$request->bonus;
        $base_salary_amount = (float)$user_info[0]->base_salary;


        $month = date("m",strtotime($request->transport_date));
        $year = date("Y",strtotime($request->transport_date));

        // get the total transportation for the selected month for a specific user
        $transport_amount = $this->transportationRepository->calculateMonthlyTranportation($user_id, $request->transport_date);

        if($transport_amount != NULL)
        {   
            $transport_amount = (float)$transport_amount[0]->transport_amount;
      
            // get the detailed amount of the transportation for this month
            $transport_details = $this->transportationRepository->getTransportationByMonth($user_id, $month, $year); 
        }
        else
        {
        $transport_amount = 0;
        $transport_details = NULL;
        }

        $total_amount = ($base_salary_amount + $transport_amount - $days_off_amount + $bonus_amount);


        $salary_is_stored = $this->salariesRepository->checkIfSalaryIsStored($user_id, $month, $year);


        return view('team.salary', array('user_info' => $user_info, 'base_salary_amount' => $base_salary_amount, 'transport_amount' => $transport_amount, 
                                         'days_off_amount' => $days_off_amount, 'bonus_amount' => $bonus_amount, 'total_amount' => $total_amount, 'month' => $request->transport_date, 'transport_details' => $transport_details, 'salary_is_stored' => $salary_is_stored,
                                            'selected_month' => $month, 'selected_year' => $year));
        
    }


    /*------------------------------------
    Save Salary in the system
    ------------------------------------- */
    public function store(Request $request, $user_id)
    {   

        // get the info of a specific user
        $user_info = $this->teamRepository->getUserInfo($user_id);

        $base_salary_amount = (float)$request->base_salary_amount;
        $transport_amount = (float)$request->transport_amount;
        $days_off_amount = (float)$request->days_off_amount;
        $bonus_amount = (float)$request->bonus_amount;
        $total_amount = (float)$request->total_amount;
        $description = $request->description;
        $salary_date = $request->transport_date;

        //store the salary as a transaction
        $this->salariesRepository->add($base_salary_amount, $transport_amount, $days_off_amount, 
                                         $bonus_amount, $total_amount, $description, $salary_date, $user_id);
                                                      
       
        // if the user is not admin
        if (\Auth::user()->role_id != 1)
         // get the accountant transactions list
            $transactionsList = $this->transactionRepository->getTransactions();
        // if the user is admin
        else
        // get all transactions
            $transactionsList = $this->transactionRepository->getAllTransactions();


          $request->session()->flash('flash_message','Salary Successfully added!');

        return view('transactions.index', array('transactionsList' => $transactionsList));
        
    }


    /*------------------------------------
    Print the salary
    ------------------------------------- */
    public function printt($user_id, $month, $year)
    {
        
        // get the info of a specific user
        $user_info = $this->teamRepository->getUserInfo($user_id);

       // get the salary details of a specific user for a specific date 
        $salary_details = $this->salariesRepository->checkIfSalaryIsStored($user_id, $month, $year);
       
        // get the trasnportation details if it exists

        if($salary_details[0]->transport_amount != 0)
        {
            // get the detailed amount of the transportation for this month
            $transport_details = $this->transportationRepository->getTransportationByMonth($user_id, $month, $year); 
        }
        else
            $transport_details = array();

        $data = array_merge($user_info, $salary_details, $transport_details);

   
        return view('team.print-salary', array('data' => $data));
    }

}
