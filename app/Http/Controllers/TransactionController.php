<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\TransactionRepository;
use App\Http\Repositories\SourcesRepository;



class TransactionController extends Controller
{
	private $transactionRepository;
    private $sourcesRepository;

    public function __construct(TransactionRepository $transactionRepository, SourcesRepository $sourcesRepository)
    {
    	$this->transactionRepository = $transactionRepository;
        $this->sourcesRepository = $sourcesRepository;
        $this->middleware('auth');
    }

    /*---------------------------------------------------------
    Shows the list of all transactions
    -------------------------------------------------------------*/
    public function index()
    {
        // if the user is not admin
        if (\Auth::user()->role_id != 1)
         // get the accountant transactions list
            $transactionsList = $this->transactionRepository->getTransactions();
        // if the user is admin
        else
        // get all transactions
            $transactionsList = $this->transactionRepository->getAllTransactions();

        return view('transactions.index', array('transactionsList' => $transactionsList));
    }

    /*---------------------------------------------------------
    Shows the details of a specific transaction
    -------------------------------------------------------------*/
    public function show($transaction_id)
    {
        //gets all information of a specific transaction
        $transactionInfo = $this->transactionRepository->getTransaction($transaction_id);

        return view('transactions.transaction-details', array('transactionInfo' => $transactionInfo));
    }


    /*---------------------------------------------------------
    Shows the form allowing to add a transaction
    -------------------------------------------------------------*/
    public function add()
    {   
        $all_sources = $this->sourcesRepository->getAllSources();

        // get the list of all the categories in the database
        $category_list = $this->transactionRepository->getAllCategories();

        return view('transactions.add-transaction', array('all_sources' => $all_sources,'category_list' => $category_list));
    }

    /*---------------------------------------------------------
    Adds a transaction in the system
    -------------------------------------------------------------*/
    public function store(Request $request)
    {   
        //if the submit button of the add transaction page is clicked, validate the input and insert them in the database
        $this->validate($request, [ 'transaction_source' => 'required', 
                                    'transaction_amount' => 'required',
                                    'transaction_description' => 'required',
                                    'transaction_category_id' => 'required',
                                    'transaction_date' => 'required']); 
        
        $amount = $request->input('transaction_amount') * (-1);

        $this->transactionRepository->addTransaction($request->input('transaction_invoice'), $request->input('transaction_source'), $amount, 
                                              $request->input('transaction_description'), $request->input('transaction_category_id'), $request->input('transaction_date'), 0);
        

        $request->session()->flash('flash_message','Transaction Successfully added!');

        // if the user is not admin
        if (\Auth::user()->role_id != 1)
         // get the accountant transactions list
            $transactionsList = $this->transactionRepository->getTransactions();
        // if the user is admin
        else
        // get all transactions
            $transactionsList = $this->transactionRepository->getAllTransactions();


        return view('transactions.index', array('transactionsList' => $transactionsList));
    }

    /*---------------------------------------------------------
    Shows the form allowing to edit a specific transaction
    -------------------------------------------------------------*/
    public function edit($transaction_id)
    {
        //gets all information of a specific transaction
        $transactionInfo = $this->transactionRepository->getTransaction($transaction_id);

        // get the list of all the categories in the database
        $category_list = $this->transactionRepository->getAllCategories();

        return view('transactions.edit-transaction', 
                    array('transaction_id' => $transaction_id, 'transactionInfo' => $transactionInfo, 'category_list' => $category_list));
    }

    /*---------------------------------------------------------
    Hides a transaction
    -------------------------------------------------------------*/
    public function hide($transaction_id)
    {
        $this->transactionRepository->hideTransaction($transaction_id);

        // if the user is not admin
        if (\Auth::user()->role_id != 1)
         // get the accountant transactions list
            $transactionsList = $this->transactionRepository->getTransactions();
        // if the user is admin
        else
        // get all transactions
            $transactionsList = $this->transactionRepository->getAllTransactions();

        $request->session()->flash('flash_message','Transaction Successfully removed!');

        return view('transactions.index', array('transactionsList' => $transactionsList));
    }


}
