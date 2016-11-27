<?php namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransportationRepository {


        /*----------------------------------
    Add a new transportation
    ------------------------------------*/
    public function addTransportation($input, $user_id)
    {   

        \DB::table('ta_transport')->insert([
            'transport_date' => $input['transport_date'],
            'place' => $input['transport_place'],
            'reason' => $input['transport_reason'],
            'price' => $input['transport_price'],
            'user_id' => $user_id
        ]);
    }


    /*----------------------------------
    Get all the transportation history for specific user
    ------------------------------------*/
    public function getTransportation($user_id)
    {   
         $q = \DB::select("SELECT *
                           FROM ta_transport 
                           WHERE user_id = :user_id",
                array(':user_id' => $user_id));
        return $q;
    }


    /*----------------------------------
    Get transportation by month for specific user
    ------------------------------------*/
    public function getTransportationByMonth($user_id, $month, $year)
    {   
         $q = \DB::select("SELECT *
                           FROM ta_transport 
                           WHERE user_id = :user_id
                           AND MONTH(transport_date) = :month
                           AND YEAR(transport_date) = :year",
                array(':user_id' => $user_id,
                      ':month' => $month,
                      ':year' => $year));
        return $q;
    }


    /*----------------------------------
    Get the transportation fees for a specific user for a specific month
    ------------------------------------*/
    public function calculateMonthlyTranportation($user_id, $selected_date)
    {   
         $q = \DB::select("SELECT SUM(price) as transport_amount
                            FROM ta_transport 
                            WHERE user_id = :user_id
                            AND MONTH(transport_date) = MONTH('".$selected_date."')
                            GROUP BY user_id",
                array(':user_id' => $user_id));
        return $q;

    }

}