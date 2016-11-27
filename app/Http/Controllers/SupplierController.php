<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\SupplierRepository;




class SupplierController extends Controller
{
	private $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository)
    {
    	$this->supplierRepository = $supplierRepository;
        $this->middleware('auth');
    }

    /*---------------------------------------------------------
    Shows the list of suppliers and their main information
    -------------------------------------------------------------*/
    public function index()
    {
        //gets the list of all suppliers
        $suppliersList = $this->supplierRepository->getAll();
        return view('suppliers.index', array('suppliersList' => $suppliersList));
    }

    /*---------------------------------------------------------
    Shows the details of a specific supplier
    -------------------------------------------------------------*/
    public function show($supplier_id)
    {
        //gets all information of a specific client
        $supplierInfo = $this->supplierRepository->getInfo($supplier_id);
        //gets all spent invoices for a supplier
        $spentTransactionsList = $this->supplierRepository->getExpenses($supplier_id);
        return view('suppliers.supplier-details', array('supplierInfo' => $supplierInfo, 'spentTransactionsList' => $spentTransactionsList));
    }

    /*---------------------------------------------------------
    Shows the form to add a new supplier
    -------------------------------------------------------------*/
    public function add()
    {
        return view('suppliers.add-supplier');
    }


    /*---------------------------------------------------------
    Save a new supplier in the system
    -------------------------------------------------------------*/
    public function store(Request $request)
    {
        //if the submit button of the add supplier page is clicked, validate the input and insert them in the database
        $this->validate($request, ['supplier_name' => 'required', 
                                    'supplier_desc' => 'required', 
                                    'supplier_email' => 'required',
                                    'supplier_phone' => 'required',
                                    'supplier_address' => 'required',
                                    'supplier_owner' => 'required',
                                    'supplier_contact' => 'required']);
       
        if ($request->hasFile('supplier_img')) 
        {  
            $file = $request->file('supplier_img');
            $file_name = time() . '-' . $file->getClientOriginalName(); 
            $file->move('images/suppliers/', $file_name);
            $request->filename = $file_name;
        }


        $this->supplierRepository->add($request->only('supplier_name', 'supplier_desc', 'supplier_email', 'supplier_phone', 'supplier_address', 'supplier_owner', 'supplier_contact', 'supplier_accounting'), $request->filename);
        $request->session()->flash('flash_message','Supplier Successfully added!');

        //gets the list of all suppliers
        $suppliersList = $this->supplierRepository->getAll();
        return view('suppliers.index', array('suppliersList' => $suppliersList));
    }

    /*---------------------------------------------------------
    Shows the interface to edit a supplier
    -------------------------------------------------------------*/
    public function edit($supplier_id)
    {
        //gets all information of a specific supplier
        $supplierInfo = $this->supplierRepository->getInfo($supplier_id);
        return view('suppliers.edit-supplier', array('supplier_id' => $supplier_id, 'supplierInfo' => $supplierInfo));
    }

    /*---------------------------------------------------------
    Edits a supplier
    -------------------------------------------------------------*/
    public function update(Request $request)
    {
        //if the submit button of the edit supplier page is clicked, validate the input and update them in the database
        $this->validate($request, ['source_id' => 'required',
                                    'source_name' => 'required', 
                                    'source_desc' => 'required', 
                                    'source_email' => 'required',
                                    'source_phone' => 'required',
                                    'source_address' => 'required',
                                    'source_owner' => 'required',
                                    'source_contact' => 'required']);

        if ($request->hasFile('supplier_img')) 
        {  
            $file = $request->file('supplier_img');
            $file_name = time() . '-' . $file->getClientOriginalName(); 
            $file->move('images/suppliers/', $file_name);
            $request->filename = $file_name;
        }

        $this->supplierRepository->update($request->only('source_id', 'source_name', 'source_desc', 'source_email', 'source_phone', 'source_address', 'source_owner', 'source_contact', 'source_accounting'), $request->filename);
        $request->session()->flash('flash_message','Supplier Successfully updated!');

        //gets all suppliers and their information 
        $suppliersList = $this->supplierRepository->getAll();
        return view('suppliers.index', array('suppliersList' => $suppliersList));
    }

    /*---------------------------------------------------------
    Hides a supplier
    -------------------------------------------------------------*/
    public function hide(Request $request, $supplier_id)
    {
        $this->supplierRepository->hide($supplier_id);

        //gets all suppliers and their information 
        $suppliersList = $this->supplierRepository->getAll();
        $request->session()->flash('flash_message','Supplier Successfully removed!');

        return view('suppliers.index', array('suppliersList' => $suppliersList));
    }




}
