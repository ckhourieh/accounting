@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Edit Client : {{ $clientInfo[0]->name }}
        </h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        @foreach($clientInfo as $ci)
                        {!! Form::open(array('route' => array('edit_client_path', $client_id))) !!}
                        <input type="hidden" class="form-control" id="source_id" name="source_id" value="{{ $ci->source_id }}">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="source_name" value="{{ $ci->name }}">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" name="source_desc">{{ $ci->desc }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="source_email" value="{{ $ci->email }}">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control" name="source_phone" value="{{ $ci->phone }}">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" rows="3" name="source_address">{{ $ci->address }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Owner</label>
                                <input type="text" class="form-control" name="source_owner" value="{{ $ci->owner }}">
                            </div>
                            <div class="form-group">
                                <label>Contact Name</label>
                                <input type="text" class="form-control" name="source_contact" value="{{ $ci->contact_name }}">
                            </div>
                            <div class="form-group">
                                <label>Accounting ID</label>
                                <input type="text" class="form-control" name="source_accounting" value="{{ $ci->accounting_id }}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Edit Client</button>
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

<a href="{{ route('clients_path') }}" class="btn btn-default btn-lg">Go Back to Client's List</a>

@endsection
