@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Client Details
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            @foreach($clientInfo as $ci)
                <div class="panel-heading">Name</div>
                <div class="panel-body">{{ $ci->name }}</div>
                <div class="panel-heading">Description</div>
                <div class="panel-body">{{ $ci->desc }}</div>
                <div class="panel-heading">Email</div>
                <div class="panel-body">{{ $ci->email }}</div>
                <div class="panel-heading">Phone</div>
                <div class="panel-body">{{ $ci->phone }}</div>
                <div class="panel-heading">Address</div>
                <div class="panel-body">{{ $ci->address }}</div>
                <div class="panel-heading">Owner</div>
                <div class="panel-body">{{ $ci->owner }}</div>
                <div class="panel-heading">Contact Name</div>
                <div class="panel-body">{{ $ci->contact_name }}</div>
                <div class="panel-heading">Accounting ID</div>
                <div class="panel-body">{{ $ci->accounting_id }}</div>
                <div class="panel-heading">Total Amount Due</div>
                <div class="panel-body"></div>
                <div class="panel-heading">Total Income</div>
                <div class="panel-body"></div>
                <div class="panel-heading">Next Payment</div>
                <div class="panel-body"></div>
                <div class="panel-heading">Timeline</div>
                <div class="panel-body"></div>
            @endforeach
        </div>
    </div>
</div>
<!-- /.row (nested) -->


<a href="{{ route('clients_path') }}" class="btn btn-default btn-lg">Go Back to Client's List</a>

@endsection
