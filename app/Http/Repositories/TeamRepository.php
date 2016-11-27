<?php namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeamRepository {

	/*-----------------------------
	Get all information from the team
	------------------------------ */
    public function getTeamInfo()
    {
        $q = \DB::select("SELECT A.*, B.description as role_desc
                            FROM users A
                            JOIN ta_roles B ON A.role_id = B.role_id
                            WHERE active = 1");
        return $q;
    }

    
    /*-----------------------------
	Get the information of a specific user
	------------------------------ */
    public function getUserInfo($user_id)
    {
        $q = \DB::select("SELECT A.*, B.description as role_desc
                            FROM users A
                            JOIN ta_roles B ON A.role_id = B.role_id
                            WHERE A.id = :user_id
                            AND active = 1",
                array(':user_id' => $user_id));
        return $q;
    }

}