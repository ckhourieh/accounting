@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-10">
        <h1 class="page-header">
            Transactions
        </h1>
    </div>
    <div class="col-md-2">
        <a href="{{ route('add_transaction_path') }}" class="btn btn-default btn-lg pull-right">Add Transaction</a>
    </div>
</div>

@if(Session::has('flash_message'))
    <div class="alert alert-success"><em> {!! session('flash_message') !!}</em></div>
@endif

<div class="row">
    <!-- Advanced Tables -->
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="clients" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Invoice ID</th>
                            <th>Client / Supplier</th>
                            <th>Contact</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactionsList as $t)             
                            <tr>
                                <td><a href="{{ route('view_transaction_details_path', $t->transaction_id) }}">{{ $t->transaction_id }}</a></td>
                                <td>{{ $t->date }} </td>
                                <td>{{ $t->invoice_id }}</td>
                                <td>{{ $t->source_name }}</td>
                                <td>{{ $t->contact }}</td>
                                @if($t->type==0)
                                    <td style="color:#d9534f">$ {{ $t->amount }}.00</td>
                                    <td style="color:#d9534f"><i class="fa fa-arrow-down"></i></td>
                                @elseif($t->type==1)
                                    <td style="color:#5cb85c">$ {{ $t->amount }}.00</td>
                                    <td style="color:#5cb85c"><i class="fa fa-arrow-up"></i></td>
                                @endif
                                <td>
                                    <a href="{{ route('edit_transaction_path', $t->transaction_id) }}"><i class="fa fa-pencil-square-o"></i></a>
                                    <a href="{{ route('hide_transaction_path', $t->transaction_id) }}"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
    <!--End Advanced Tables -->
</div>

<!-- DATA TABLE -->
<link rel="stylesheet" type="text/css" href="/js/dataTables/dataTables.bootstrap.min.css">
<script src="/js/dataTables/jquery.dataTables.min.js"></script>
<script src="/js/dataTables/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#clients').DataTable();
    });
</script>

@endsection
