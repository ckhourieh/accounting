@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Add Client
        </h1>
    </div>
</div>

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                         {!! Form::open(array('route' => array('add_client_path'), 'files' => true)) !!}
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="client_name" placeholder="Enter Client Name" value="{{  Request::old('client_name') }}">
                            </div>

                            <div class="form-group">
                                <label>Logo</label>
                                <input type="file" name="client_img">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" name="client_desc" placeholder="Enter a brief description">{{  Request::old('client_desc') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="client_email" placeholder="Enter Client Email" value="{{  Request::old('client_email') }}">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control" name="client_phone" placeholder="Enter Client Phone" value="{{  Request::old('client_phone') }}">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" rows="3" name="client_address" placeholder="Enter Client Address">{{  Request::old('client_address') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Owner</label>
                                <input type="text" class="form-control" name="client_owner" placeholder="Enter Owner Name" value="{{  Request::old('client_owner') }}">
                            </div>
                            <div class="form-group">
                                <label>Contact Name</label>
                                <input type="text" class="form-control" name="client_contact" placeholder="Enter Contact Name" value="{{  Request::old('client_contact') }}">
                            </div>
                            <div class="form-group">
                                <label>Accounting ID</label>
                                <input type="text" class="form-control" name="client_accounting" placeholder="Enter Client Accounting ID" value="{{  Request::old('client_accounting') }}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Add Client</button>
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

<a href="{{ route('clients_path') }}" class="btn btn-default btn-lg">Go Back to Client's List</a>

@endsection
