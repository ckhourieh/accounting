<?php namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalariesRepository {


         /*----------------------------------
        store salary in the database + transaction
    ------------------------------------*/

    public function add($base_salary_amount, $transport_amount, $days_off_amount, 
                              $bonus_amount, $total_amount, $description, $salary_date, $user_id)
    {
        \DB::transaction(function() use ($base_salary_amount, $transport_amount, $days_off_amount, 
                                         $bonus_amount, $total_amount, $description, $salary_date, $user_id) 
        {
            
            \DB::table('ta_salary')->insert([
                        'user_id' => $user_id,
                        'salary_date' => $salary_date, 
                        'base_salary_amount' => $base_salary_amount,
                        'transport_amount' => $transport_amount,
                        'days_off_amount' => $days_off_amount,
                        'bonus_amount' => $bonus_amount,
                        'total_amount' => $total_amount,
                        'created_at' => Carbon::now('Asia/Beirut'),
                        'updated_at' => Carbon::now('Asia/Beirut')
                    ]);


            //get the salary_id of the last salary input
            $salary_id = \DB::select("SELECT salary_id FROM ta_salary ORDER BY salary_id DESC LIMIT 0,1");
            $salary_id = $salary_id[0]->salary_id;
            
            

            \DB::table('ta_transactions')->insert([
                        'source_id' => 18,
                        'amount' => $total_amount,
                        'description' => $description,
                        'date' => $salary_date, 
                        'type' => 0,
                        'hidden' => 0,
                        'salary_id' => $salary_id,
                        'created_at' => Carbon::now('Asia/Beirut'),
                        'updated_at' => Carbon::now('Asia/Beirut')
                    ]);

        });
    }


     public function checkIfSalaryIsStored($user_id, $month, $year)
    {   
         $q = \DB::select(" SELECT  *
                            FROM ta_salary 
                            WHERE user_id = :user_id
                            AND MONTH(salary_date) = :month
                            AND YEAR(salary_date) = :year",
                array(':user_id' => $user_id,
                      ':month' => $month,
                      ':year' => $year));
        return $q;

    }


}