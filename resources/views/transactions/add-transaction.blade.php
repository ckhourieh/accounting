@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Add Transaction
        </h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        {!! Form::open(array('route' => 'add_transaction_path')) !!}
                            <div class="form-group">
                                <label>Invoice ID</label>
                                <input type="text" class="form-control" name="transaction_invoice" placeholder="Enter Invoice ID">
                            </div>
                            <div class="form-group">
                                <label>Client / Supplier</label>
                                <input type="text" class="form-control" name="transaction_source" placeholder="Enter Transaction Client or Supplier">
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" name="transaction_amount" placeholder="Enter Transaction Amount">
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Contact</label>
                                <input type="text" class="form-control" name="transaction_contact" placeholder="Enter Transaction Contact Person">
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="transaction_date">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Type</label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="transaction_type" id="transaction_type0" value="0">Out
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="transaction_type" id="transaction_type1" value="1">In
                                </label>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Add Transaction</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<a href="{{ route('transactions_path') }}" class="btn btn-default btn-lg">Go Back to Transactions' List</a>

@endsection
