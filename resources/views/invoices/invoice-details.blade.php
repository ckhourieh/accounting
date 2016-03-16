@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Invoice Details
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            @foreach($invoiceInfo as $ii)
                <div class="panel-heading">Invoice ID</div>
                <div class="panel-body">{{ $ii->invoice_id }}</div>
                <div class="panel-heading">Invoice Title</div>
                <div class="panel-body">{{ $ii->title }}</div>
                <div class="panel-heading">Client Name</div>
                <div class="panel-body">{{ $ii->client_name }}</div>
                <div class="panel-heading">Amount</div>
                <div class="panel-body">$ {{ $ii->amount }}.00</div>
                <div class="panel-heading">Due Date</div>
                <div class="panel-body">{{ $ii->due_date }}</div>
                <div class="panel-heading">Next Payments</div>
                <div class="panel-body"></div>
                <div class="panel-heading">Items</div>
                <div class="panel-body">
                    @foreach($invoiceItems as $in)
                    <div class="panel-heading">{{ $in->title }}</div>
                    <div class="panel-body">${{ $in->item_amount }}.00</div>
                    @endforeach
                </div>
                <div class="panel-heading">Status</div>
                <div class="panel-body">{{ $ii->status_name }}</div>
                <div class="panel-heading">Paid</div>
                <div class="panel-body">$ {{ $ii->paid }}.00</div>
                <div class="panel-heading">Remaining</div>
                <div class="panel-body">$ {{ $ii->amount - $ii->paid }}.00</div>
                <div class="panel-heading">Creation Date</div>
                <div class="panel-body">{{ $ii->created_at }}</div>
            @endforeach
        </div>
    </div>
</div>
<!-- /.row (nested) -->

<a href="{{ route('invoices_path') }}" class="btn btn-default btn-lg">Go Back to Invoices' List</a>

@endsection
