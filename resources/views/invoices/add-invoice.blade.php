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
                    <div class="col-lg-12 form">
                        {!! Form::open(array('route' => 'add_invoice_path')) !!}

                            <div class="form-group">
                                <label>Client Name</label>
                                <select class="form-control" name="invoice_client">
                                    @foreach($clients as $cl)
                                    <option value="{{ $cl->source_id }}">{{ $cl->name }}</option>
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
                                <label>Select Service</label>
                                <select class="form-control" name="invoice_item_service_1">
                                    @foreach($services as $s)
                                    <option value="{{ $s->service_type_id }}">{{ $s->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="invoice_item_description_1"></textarea>
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
                                <label>Next Payment</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="invoice_next_payment">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="item_number"><input type="hidden" class="form-control" name="item_number" value="1"></div>

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
        $('.item-content').append('<div class="form-group"><label>Select Service</label><select class="form-control" name="invoice_item_service_'+i+'">@foreach($services as $s)<option value="{{ $s->service_type_id }}">{{ $s->title }}</option>@endforeach</select></div><div class="form-group"><label>Description</label><textarea class="form-control" name="invoice_item_description_'+i+'"></textarea></div><div class="form-group"><label>Amount</label><div class="form-group input-group"><span class="input-group-addon">$</span><input type="text" class="form-control" name="invoice_item_amount_'+i+'" placeholder="Enter Item Amount"><span class="input-group-addon">.00</span></div></div>');
        $('.item_number').append('<input type="hidden" class="form-control" name="item_number" value="'+i+'">');
        i++;
    });
</script>

@endsection
