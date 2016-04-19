@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Add Supplier
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
                        {!! Form::open(array('route' => array('add_supplier_path'), 'files' => true)) !!}
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="supplier_name" placeholder="Enter Supplier Name" value="{{  Request::old('supplier_name') }}">
                            </div>

                            <div class="form-group">
                                <label>Logo</label>
                                <input type="file" name="supplier_img">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" name="supplier_desc" placeholder="Enter a brief description" value="{{  Request::old('supplier_desc') }}"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="supplier_email" placeholder="Enter Supplier Email" value="{{  Request::old('supplier_email') }}">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control" name="supplier_phone" placeholder="Enter Supplier Phone" value="{{  Request::old('supplier_phone') }}">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" rows="3" name="supplier_address" placeholder="Enter Supplier Address">{{  Request::old('supplier_address') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Owner</label>
                                <input type="text" class="form-control" name="supplier_owner" placeholder="Enter Owner Name" value="{{  Request::old('supplier_owner') }}">
                            </div>
                            <div class="form-group">
                                <label>Contact Name</label>
                                <input type="text" class="form-control" name="supplier_contact" placeholder="Enter Contact Name" value="{{  Request::old('supplier_contact') }}">
                            </div>
                            <div class="form-group">
                                <label>Accounting ID</label>
                                <input type="text" class="form-control" name="supplier_accounting" placeholder="Enter Supplier Accounting ID" value="{{  Request::old('supplier_accounting') }}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Add Supplier</button>
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

<a href="{{ route('suppliers_path') }}" class="btn btn-default btn-lg">Go Back to Supplier's List</a>

@endsection
