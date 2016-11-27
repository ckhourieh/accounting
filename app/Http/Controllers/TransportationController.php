<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\TransportationRepository;
use App\Http\Repositories\TeamRepository;



class TransportationController extends Controller
{
    private $teamRepository;
    private $transportationRepository;

    public function __construct(TransportationRepository $transportationRepository, TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->transportationRepository = $transportationRepository;
        $this->middleware('auth');
    }

    /* --------------------------------
    View and add transportation
    -----------------------------------*/
    public function add($user_id)
    {
        
        // get the info of a specific user
        $user_info = $this->teamRepository->getUserInfo($user_id);
        // get the transportation of a specific user
        $transportation_info = $this->transportationRepository->getTransportation($user_id);
        return view('team.transportation', array('user_info' => $user_info, 'transportation_info' => $transportation_info));
    }

    /* --------------------------------
    Store the transportation to the system associated to a specific user
    -----------------------------------*/
    public function store(Request $request, $user_id)
    {
        
        //if the submit button of the add transportation page is clicked, validate the input and insert them in the database
        $this->validate($request, [ 'transport_date' => 'required', 
                                    'transport_place' => 'required',
                                    'transport_reason' => 'required',
                                    'transport_price' => 'required']); 
        //add the new transporation
        $this->transportationRepository->addTransportation($request->only('transport_date', 'transport_place', 'transport_reason', 'transport_price'), $user_id);   
        $request->session()->flash('flash_message','Transportation successfully added!');
        // get the info of a specific user
        $user_info = $this->teamRepository->getUserInfo($user_id);
        // get the transportation of a specific user
        $transportation_info = $this->transportationRepository->getTransportation($user_id);
    
        return view('team.transportation', array('user_info' => $user_info, 'transportation_info' => $transportation_info));
    }



}
