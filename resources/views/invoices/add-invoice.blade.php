@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Add Invoice
        </h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        {!! Form::open(array('route' => 'add_invoice_path')) !!}
                            <div class="form-group">
                                <label>Invoice ID</label>
                                <input type="text" class="form-control" name="invoice_id" placeholder="Enter Invoice ID">
                            </div>

                            <div class="form-group">
                                <label>Invoice Title</label>
                                <input type="text" class="form-control" name="invoice_title" placeholder="Enter Invoice Title">
                            </div>

                            <div class="form-group">
                                <label>Client Name</label>
                                <select class="form-control" name="invoice_client">
                                    @foreach($clients as $cl)
                                    <option value="{{ $cl->client_id }}">{{ $cl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Due Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="invoice_date">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Frequency Of This Invoice</label>
                                <select class="form-control" name="invoice_frequency">
                                    @foreach($frequencies as $f)
                                    <option value="{{ $f->frequency_id }}">{{ $f->frequency }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Item Title</label>
                                <input type="text" class="form-control" name="invoice_item_title_1" placeholder="Enter Item Title">
                            </div>

                            <div class="form-group">
                                <label>Amount</label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" name="invoice_item_amount_1" placeholder="Enter Item Amount">
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>

                            <div class="item-content"></div>

                            <div class="form-group">
                                <div class="btn btn-default item">Add Item</div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Add Invoice</button>
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

<a href="{{ route('invoices_path') }}" class="btn btn-default btn-lg">Go Back to Invoices' List</a>

<script type="text/javascript">
    var i =2;
    $('.item').click(function(){
        $('.item-content').append('<div class="form-group"><label>Item Title</label><input type="text" class="form-control" name="invoice_item_title_'+i+'" placeholder="Enter Item Title"></div><div class="form-group"><label>Amount</label><div class="form-group input-group"><span class="input-group-addon">$</span><input type="text" class="form-control" name="invoice_item_amount_'+i+'" placeholder="Enter Item Amount"><span class="input-group-addon">.00</span></div></div>');
        i++;
    });
</script>

@endsection
