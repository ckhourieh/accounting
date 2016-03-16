@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Add Supplier
        </h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        {!! Form::open(array('route' => 'add_supplier_path')) !!}
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="supplier_name" placeholder="Enter Supplier Name">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" name="supplier_desc" placeholder="Enter a brief description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="supplier_email" placeholder="Enter Supplier Email">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control" name="supplier_phone" placeholder="Enter Supplier Phone">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" rows="3" name="supplier_address" placeholder="Enter Supplier Address"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Owner</label>
                                <input type="text" class="form-control" name="supplier_owner" placeholder="Enter Owner Name">
                            </div>
                            <div class="form-group">
                                <label>Contact Name</label>
                                <input type="text" class="form-control" name="supplier_contact" placeholder="Enter Contact Name">
                            </div>
                            <div class="form-group">
                                <label>Accounting ID</label>
                                <input type="text" class="form-control" name="supplier_accounting" placeholder="Enter Supplier Accounting ID">
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
