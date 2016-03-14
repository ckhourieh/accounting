@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Transaction Details
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            @foreach($transactionInfo as $ti)
                <div class="panel-heading">Invoice ID</div>
                <div class="panel-body">{{ $ti->invoice_id }}</div>
                <div class="panel-heading">Client / Supplier</div>
                <div class="panel-body">{{ $ti->source }}</div>
                <div class="panel-heading">Amount</div>
                <div class="panel-body">$ {{ $ti->amount }}.00</div>
                <div class="panel-heading">Contact</div>
                <div class="panel-body">{{ $ti->contact }}</div>
                <div class="panel-heading">Date</div>
                <div class="panel-body">{{ $ti->date }}</div>
                <div class="panel-heading">Type</div>
                <div class="panel-body">{{ $ti->type }}</div>
            @endforeach
        </div>
    </div>
</div>
<!-- /.row (nested) -->

<a href="{{ route('transactions_path') }}" class="btn btn-default btn-lg">Go Back to Transactions' List</a>

@endsection
