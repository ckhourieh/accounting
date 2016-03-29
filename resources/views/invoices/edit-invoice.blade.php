@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Edit Invoice # {{ $invoiceInfo[0]->invoice_id }}
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

                            <h3>{{ $ii->client_name }}</h3>
                            <br>

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

                            <?php $i=1; ?>
                            @foreach($invoiceItems as $in)
                            <div class="row">
                                <div class="col-xs-12">
                                    <input type="hidden" class="form-control" name="invoice_item_id_{{$i}}" value="{{ $in->invoice_item_id }}">

                                    <div class="form-group">
                                        <p>{{ $in->service_title }}</p>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Description</label>
                                        <textarea class="form-control" name="invoice_item_description_{{$i}}">{{ $in->description }}</textarea>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Amount</label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" class="form-control" name="invoice_item_amount_{{$i}}" value="{{ $in->item_amount }}">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $i++; ?> 
                            @endforeach

                            <input type="hidden" class="form-control" name="item_number" value="{{ $i }}">

                            <div class="form-group">
                                <label>Status</label>
                                @foreach($status as $s)
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="invoice_status" id="invoice_status1" value="{{$s->status_id}}" <?php if($ii->status_id == $s->status_id) echo 'checked="checked"'; ?>>{{$s->name}}
                                    </label>
                                </div>
                                @endforeach
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
