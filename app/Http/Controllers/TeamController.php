<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\TeamRepository;




class TeamController extends Controller
{

    private $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->middleware('auth');
    }

    /* --------------------------------
    SHow the team members 
    -----------------------------------*/
    public function index()
    {
        
        // get all the info of the team
        $teamInfo = $this->teamRepository->getTeamInfo();
        return view('team.index', array('teamInfo' => $teamInfo));
    }

    /* --------------------------------
    SHow the detailed information of a specific team member 
    -----------------------------------*/
    public function show($user_id)
    {
        
        // get the info of a specific user
        $user_info = $this->teamRepository->getUserInfo($user_id);
        return view('team.profile-details', array('user_info' => $user_info));
    }



}
