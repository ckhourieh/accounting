<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\ClientRepository;


class ClientController extends Controller
{
	private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
    	$this->clientRepository = $clientRepository;
        $this->middleware('auth');
    }

    /*---------------------------------------------------------
    Shows the list of clients and their main information
    -------------------------------------------------------------*/
    public function index()
    {
        //gets the list of all clients
        $clientsList = $this->clientRepository->getAll();
        return view('clients.index', array('clientsList' => $clientsList));
    }

    /*---------------------------------------------------------
    Shows the details of a specific client
    -------------------------------------------------------------*/
    public function show($client_id)
    {
        //gets all information of a specific client
        $clientInfo = $this->clientRepository->getInfo($client_id);
        //gets all the invoices due by the client
        $clientInvoices = $this->clientRepository->getInvoices($client_id);

        return view('clients.client-details', array('clientInfo' => $clientInfo, 'clientInvoices' => $clientInvoices));
    }

    /*---------------------------------------------------------
    Shows the form to add a client
    -------------------------------------------------------------*/
    public function add()
    {  
        return view('clients.add-client');
    }


    /*---------------------------------------------------------
    Adds a client
    -------------------------------------------------------------*/
    public function store(Request $request)
    {
        //if the submit button of the add client page is clicked, validate the input and insert them in the database
        $this->validate($request, ['client_name' => 'required', 
                                    'client_desc' => 'required', 
                                    'client_email' => 'required',
                                    'client_phone' => 'required',
                                    'client_address' => 'required',
                                    'client_owner' => 'required',
                                    'client_contact' => 'required']);

        if ($request->hasFile('client_img')) 
        {  
            $file = $request->file('client_img');
            $file_name = time() . '-' . $file->getClientOriginalName(); 
            $file->move('images/clients/', $file_name);
            $request->filename = $file_name;
        }


        $this->clientRepository->add($request->only('client_name', 'client_desc', 'client_email', 'client_phone', 'client_address', 'client_owner', 'client_contact', 'client_accounting'), $request->filename);
        $request->session()->flash('flash_message','Client Successfully added!');

        //gets all clients and their information 
        $clientsList = $this->clientRepository->getAll();
        return view('clients.index', array('clientsList' => $clientsList));
    }

    /*---------------------------------------------------------
    Shows the form to edit a client
    -------------------------------------------------------------*/
    public function edit($client_id)
    {
        //gets all information of a specific client
        $clientInfo = $this->clientRepository->getInfo($client_id);
        return view('clients.edit-client', array('client_id' => $client_id, 'clientInfo' => $clientInfo));
    }

    /*---------------------------------------------------------
    Edits a client
    -------------------------------------------------------------*/
    public function update(Request $request)
    {   
        //gets all information of a specific client
        $clientInfo = $this->clientRepository->getInfo($request->input('source_id'));

        //if the submit button of the edit client page is clicked, validate the input and update them in the database
        $this->validate($request, ['source_id' => 'required',
                                    'source_name' => 'required', 
                                    'source_desc' => 'required', 
                                    'source_email' => 'required',
                                    'source_phone' => 'required',
                                    'source_address' => 'required',
                                    'source_owner' => 'required',
                                    'source_contact' => 'required']);

        if ($request->hasFile('client_img')) 
        {  
            $file = $request->file('client_img');
            $file_name = time() . '-' . $file->getClientOriginalName(); 
            $file->move('images/clients/', $file_name);
            $request->filename = $file_name;
        }

        else if($clientInfo[0]->img != NULL)
            $request->filename = $clientInfo[0]->img;

        $this->clientRepository->update($request->only('source_id', 'source_name', 'source_desc', 'source_email', 'source_phone', 'source_address', 'source_owner', 'source_contact', 'source_accounting'), $request->filename);
        $request->session()->flash('flash_message','Client Successfully updated!');

        //gets all clients and their information 
        $clientsList = $this->clientRepository->getAll();
        return view('clients.index', array('clientsList' => $clientsList));
    }

    /*----------------------------------
    Hide cleints by updating hidden attribute to 1
    ------------------------------------*/
    public function hide(Request $request, $client_id)
    {
        $this->clientRepository->hide($client_id);

        //gets all clients and their information 
        $clientsList = $this->clientRepository->getAll();
        $request->session()->flash('flash_message','Client Successfully removed!');

        return view('clients.index', array('clientsList' => $clientsList));
    }



}
