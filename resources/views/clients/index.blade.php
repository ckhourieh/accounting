@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-10">
        <h1 class="page-header">
            Clients
        </h1>
    </div>
    <div class="col-md-2">
        <a href="{{ route('add_client_path') }}" class="btn btn-default btn-lg pull-right">Add Client</a>
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Total Amount Due</th>
                            <th>Total Income</th>
                            <th>Next Payments</th>
                            <th>Timeline</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientsList as $c)             
                            <tr>
                                <td>{{ $c->client_id }}</td>
                                <td><a href="{{ route('view_client_details_path', $c->client_id) }}">{{ $c->name }}</a></td>
                                <td><a href="{{ route('total_amount_due_path', $c->client_id) }}" style="color:#d9534f">$ {{ $c->total_amount_due }}.00</a></td>
                                <td><a href="{{ route('total_income_path', $c->client_id) }}" style="color:#5cb85c">$ {{ $c->total_income }}.00</a></td>
                                <td><a href="{{ route('next_payments_path', $c->client_id) }}">{{ $c->next_payments }}</a></td>
                                <td><a href="{{ route('client_timeline_path', $c->client_id) }}">See Graph</a></td>
                                <td>
                                    <a href="{{ route('edit_client_path', $c->client_id) }}"><i class="fa fa-pencil-square-o"></i></a>
                                    <a href="{{ route('hide_client_path', $c->client_id) }}"><i class="fa fa-trash-o"></i></a>
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
