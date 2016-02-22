@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Edit Transaction
        </h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        @foreach($transactionInfo as $ti)
                        {!! Form::open(array('route' => array('edit_transaction_path', $transaction_id))) !!}
                        <input type="hidden" class="form-control" id="transaction_id" name="transaction_id" value="{{ $ti->transaction_id }}">
                            <div class="form-group">
                                <label>Invoice ID</label>
                                <input type="text" class="form-control" name="transaction_invoice" value="{{ $ti->invoice_id }}">
                            </div>
                            <div class="form-group">
                                <label>Client / Supplier</label>
                                <input type="text" class="form-control" name="transaction_source" value="{{ $ti->source }}">
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" name="transaction_amount" value="{{ $ti->amount }}">
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Contact</label>
                                <input type="text" class="form-control" name="transaction_contact" value="{{ $ti->contact }}">
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="transaction_date" value="{{ $ti->date }}">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Type</label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="transaction_type" id="transaction_type0" value="0" <?php if($ti->type == '0') echo 'checked="checked"'; ?>>Out
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="transaction_type" id="transaction_type1" value="1" <?php if($ti->type == '1') echo 'checked="checked"'; ?>>In
                                </label>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Edit Transaction</button>
                            </div>
                        {!! Form::close() !!}
                        @endforeach
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
