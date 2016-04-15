@extends('layouts.app')

@section('content')


<style type="text/css">

.btn-default.active {
    background-color: #449d44;
    color:white;
}
</style>



<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Edit Invoice # {{ $invoiceInfo[0]->invoice_id }}
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


@if($invoiceInfo[0]->status_id == 6)

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">

                        {!! Form::open(array('route' => array('edit_invoice_path', $invoice_id))) !!}
                        <input type="hidden" class="form-control" id="invoice_id" name="invoice_id" value="{{ $invoiceInfo[0]->invoice_id }}">

                            <h3>{{ $invoiceInfo[0]->client_name }}</h3>
                            <br>

                            <div class="form-group">
                                <label>Amount</label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" name="invoice_amount" value="{{ $invoiceInfo[0]->amount }}" disabled>
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Date</label>
                                <div class="form-group">
                                 <!-- View website http://eonasdan.github.io/bootstrap-datetimepicker/ -->
                                 <div class='input-group date' id='datetimepicker1'>
                                     <input type="text" class="form-control" name="invoice_date" value="{{ $invoiceInfo[0]->due_date }}">
                                     <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                     </span>
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
                                        <textarea class="form-control" name="invoice_item_description_{{$i}}">{{ preg_replace('/<br\\s*?\/??>/i', '', $in->description) }}</textarea>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Amount</label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" class="form-control item_amount" name="invoice_item_amount_{{$i}}" value="{{ $in->item_amount }}">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $i++; ?> 
                            @endforeach

                            <input type="hidden" class="form-control" name="item_number" value="{{ $i }}">


                             <div class="form-group">
                                <label>Status</label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    @foreach($status as $s)
                                        <!-- display all status except: "Overdue" and "sent" -->
                                        @if($s->status_id != 4 && $s->status_id != 1)
                                            <label id="out" class="btn btn-default <?php if($invoiceInfo[0]->status_id == $s->status_id) echo 'active'; ?>">
                                                <input type="radio" name="invoice_status" id="invoice_status1" value="{{$s->status_id}}" <?php if($invoiceInfo[0]->status_id == $s->status_id) echo 'checked="checked"'; ?>>
                                            {{$s->name}} 
                                            </label>     
                                        @endif                             
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Edit Invoice</button>
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

@else <!-- if($invoiceInfo[0]->status == 6)  -->


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                       
                       
                            <h3 style="float:left">{{ $invoiceInfo[0]->client_name }}</h3>
                            <br>


                            <div class="form-group" style="text-align:right">
                                <label>Due Date: &nbsp&nbsp </label>{{ $invoiceInfo[0]->due_date }}
                            </div>



                             <ul style="list-style-type:none; height:35px; background:#000; padding: 0 30px;">
                                <li style="padding:5px; font-weight:bold; width:50%; float:left; display:inline-block; color:#fff;">
                                    Item
                                </li>
                                
                                <li style="padding:5px; text-align:right; font-weight:bold; width:50%; float:right; display:inline-block; color:#fff;">
                                    Price
                                </li>
                             </ul>
                                
                            <?php $i=1; ?>
                            @foreach($invoiceItems as $in)

                                <ul style="list-style-type:none; padding: 0 30px;">
                                    <li style="padding:5px; float:left; width:80%; display:inline-block;">
                                        <b>{{ $in->service_title }}</b><br>
                                        {{ $in->description }}
                                    </li>
                                    
                                    <li style="padding:5px; text-align:right; float:right; width:20%; display:inline-block;">
                                        ${{ $in->item_amount }}.00
                                    </li>
                                </ul>
                            @endforeach
                            
                                <ul style="list-style-type:none; padding: 0 30px;">
                                    <li style="padding:5px; float:left; width:50%; display:inline-block;"></li>
                                    <li style="padding:5px; text-align:right; border-top:2px solid #eee; font-weight:bold; float:right; width:50%; display:inline-block;">
                                       Total: ${{ $invoiceInfo[0]->amount }}.00
                                    </li>
                                </ul>
                        
                        <div style="margin-top:140px">

                         <label>Actual status: &nbsp&nbsp </label><span style="color:{{$invoiceInfo[0]->color_code}}"><b>{{ $invoiceInfo[0]->status_name }}</b></span> 
                         <br><br>
                            {!! Form::open(array('route' => array('edit_no_draft_invoice_path', $invoice_id))) !!}        
                                <input type="hidden" class="form-control" name="item_number" value="{{ $i }}">
                                <input type="hidden" class="form-control" name="invoice_id" value="{{ $invoice_id }}">

                                 <div class="form-group">
                                    <label>Change status to:</label><br>

                                   <!-- if status is "sent" or "overdue" -->
                                   @if($invoiceInfo[0]->status_id == 1 || $invoiceInfo[0]->status_id == 4)
                                        <div class="btn-group" data-toggle="buttons">
                                            @foreach($status as $s)
                                                <!-- display only the following status: "paid", "incomplete", "not paid" -->
                                                @if($s->status_id == 3 || $s->status_id == 2 || $s->status_id == 5)
                                                    <label id="out" class="btn btn-default <?php if($invoiceInfo[0]->status_id == $s->status_id) echo 'active'; ?>">
                                                        <input type="radio" name="invoice_status" id="invoice_status1" value="{{$s->status_id}}" <?php if($invoiceInfo[0]->status_id == $s->status_id) echo 'checked="checked"'; ?>>
                                                    {{$s->name}} 
                                                    </label> 
                                                @endif                                 
                                            @endforeach
                                        </div>
                                     <!-- if status is "incomplete" or "not paid" -->
                                    @elseif($invoiceInfo[0]->status_id == 2 || $invoiceInfo[0]->status_id == 5)
                                        <div class="btn-group" data-toggle="buttons">
                                            @foreach($status as $s)
                                                <!-- display only the following status: "paid", "incomplete" -->
                                                @if($s->status_id == 2 || $s->status_id == 2 || $s->status_id == 3)
                                                    <label id="out" class="btn btn-default <?php if($invoiceInfo[0]->status_id == $s->status_id) echo 'active'; ?>">
                                                        <input type="radio" name="invoice_status" id="invoice_status1" value="{{$s->status_id}}" <?php if($invoiceInfo[0]->status_id == $s->status_id) echo 'checked="checked"'; ?>>
                                                    {{$s->name}} 
                                                    </label> 
                                                @endif                                 
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                 <div class="form-group">
                                    <label> Enter new payment:</label><br>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="new_payment" class="onoffswitch-checkbox" id="myonoffswitch" value="1">
                                        <label class="onoffswitch-label" for="myonoffswitch">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                 </div>
                               

                                <div id="new_payment_input" class="form-group" style="display:none;">
                                    <label>Paid amount in USD</label>
                                    <input type="text" class="form-control" name="invoice_paid" value="{{ $invoiceInfo[0]->paid }}">
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg">Edit Invoice</button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                     
                    </div>
                </div>
                <!-- /.row (nested) -->

                <br/>
                <div class="row">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3 style="float:left"> Payments details</h3>

                            <div class="form-group" style="text-align:right">
                                <label style="color:#c9302c">Remaining amount: &nbsp&nbsp </label> <span style="color:#c9302c">$ {{ ($invoiceInfo[0]->amount - $invoiceInfo[0]->paid) }}</span>
                            </div>

                            <br/>

                            <div class="table-responsive">
                                <table id="clients" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Transaction #</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Amount ($)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoiceTransactions as $i)             
                                            <tr>
                                                <td><a href="{{ route('view_transaction_details_path', $i->transaction_id) }}">{{ $i->transaction_id }}</a></td>
                                                <td>{{ $i->date }} </td>
                                                <td>{{ $i->description }}</td>
                                                <td>$ {{ $i->amount }}</td> 
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color:#398439; color:white;">
                                            <td style="text-align:center" colspan="3">Total paid</td>
                                            <td><b>$ {{ $invoiceInfo[0]->paid }}<b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>





            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

@endif <!-- if($invoiceInfo[0]->status == 6)  -->



<a href="{{ route('invoices_path') }}" class="btn btn-default btn-lg">Go Back to Invoices' List</a>



<script src="../js/moment.js"></script>
 <script src="../js/datepicker.js"></script>
 <script type="text/javascript">
     $(function () {
         $('#datetimepicker1').datetimepicker({
             pickTime: false,
             format: 'YYYY-MM-DD'
         });
     });
 </script>

<script type="text/javascript">


$(':checkbox').click(function(){

$('#new_payment_input').slideToggle();

})




// function that add thounsand space separator for big numbers
     function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + " " + '$2');
        }
        return x1 + x2;
    }



// calculate the amount in LBP from the USD amount
  $(".item_amount").keyup(function(){
        
         var sum = 0;
            $(".item_amount").each(function(){
                sum += +$(this).val();
            });

        $("input[name='invoice_amount']").val(sum);
            
    });

 </script>


@endsection
