@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-10">
        <h1 class="page-header">
            Total amount spent for {{ $spentTransactionsList[0]->supplier_name }}
        </h1>
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
                <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Transaction #</th>
                            <th>Date</th>
                            <th>Supplier Name</th>
                            <th>Amount</th>
                            <th>Contact Person</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($spentTransactionsList as $i)             
                            <tr>
                                <td><a href="{{ route('view_transaction_details_path', $i->transaction_id) }}">{{ $i->transaction_id }}</a></td>
                                <td>{{ $i->date }}</td>
                                <td>{{ $i->supplier_name }}</td>
                                <td style="color:#d9534f">$ {{ number_format($i->amount) }}.00</td>
                                <td>{{ $i->contact_name }}</td>
                                <td>
                                    <a href="{{ route('edit_transaction_path', $i->transaction_id) }}"><i class="fa fa-pencil-square-o"></i></a>
                                    <a href="{{ route('hide_transaction_path', $i->transaction_id) }}"><i class="fa fa-trash-o"></i></a>
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

<script type="text/javascript">
    $(document).ready(function () {
        $('#clients').DataTable({
            "order": [[ 0, "desc" ]]
        });
    });
</script>
@endsection
