@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Edit Invoice
        </h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        @foreach($invoiceInfo as $ii)
                        {!! Form::open(array('route' => array('edit_invoice_path', $invoice_id))) !!}
                        <input type="hidden" class="form-control" id="invoice_id" name="invoice_id" value="{{ $ii->invoice_id }}">

                            <div class="form-group">
                                <label>Invoice Title</label>
                                <input type="text" class="form-control" name="invoice_title" value="{{ $ii->title }}">
                            </div>

                            <div class="form-group">
                                <label>Client Name</label>
                                <select class="form-control" name="invoice_client">
                                    @foreach($clients as $cl)
                                    <option value="{{ $cl->client_id }}" <?php if($ii->client_id == '{{ $cl->client_id }}') echo 'selected="selected"'; ?>>{{ $cl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount</label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" name="invoice_amount" value="{{ $ii->amount }}" disabled>
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Due Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="invoice_date" value="{{ $ii->due_date }}">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Frequency Of This Invoice</label>
                                <select class="form-control" name="invoice_frequency">
                                    @foreach($frequencies as $f)
                                    <option value="{{ $f->frequency_id }}" <?php if($ii->frequency_id == '{{ $f->frequency_id }}') echo 'selected="selected"'; ?>>{{ $f->frequency }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <?php $i=1; ?>
                            @foreach($invoiceItems as $in)
                            <input type="hidden" class="form-control" name="invoice_item_id_{{$i}}" value="{{ $in->invoice_item_id }}">
                            <div class="form-group">
                                <label>Item Title</label>
                                <input type="text" class="form-control" name="invoice_item_title_{{$i}}" value="{{ $in->title }}">
                            </div>

                            <div class="form-group">
                                <label>Amount</label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" name="invoice_item_amount_{{$i}}" value="{{ $in->item_amount }}">
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>
                            <?php $i++; ?> 
                            @endforeach

                            <div class="form-group">
                                <label>Status</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="invoice_status" id="invoice_status1" value="0" <?php if($ii->status == '0') echo 'checked="checked"'; ?>>Not Paid
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="invoice_status" id="invoice_status2" value="1" <?php if($ii->status == '1') echo 'checked="checked"'; ?>>Paid
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="invoice_status" id="invoice_status3" value="2" <?php if($ii->status == '2') echo 'checked="checked"'; ?>>Not Complited
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Paid</label>
                                <input type="text" class="form-control" name="invoice_paid" value="{{ $ii->paid }}">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Edit Invoice</button>
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

<a href="{{ route('invoices_path') }}" class="btn btn-default btn-lg">Go Back to Invoices' List</a>

@endsection
