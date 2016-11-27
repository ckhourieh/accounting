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
            Edit Invoice # {{ $invoiceInfo[0]->invoice_id }} <span style="color:{{$invoiceInfo[0]->color_code}}">[{{ $invoiceInfo[0]->status_name }}]</span>
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
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Due Date</label>
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

                            <br>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md">Edit Invoice</button>
                                <a onclick="return confirm('Are you sure you want to send the invoice number {{$invoiceInfo[0]->invoice_id}}?')" href="{{ route('send_invoice_path', $invoiceInfo[0]->invoice_id) }}"  class="btn btn-success btn-md">Send Invoice</a>
                                <a onclick="return confirm('Are you sure you want to remove this invoice number {{$invoiceInfo[0]->invoice_id}} from the list?');" href="{{ route('hide_invoice_path', $invoiceInfo[0]->invoice_id) }}" class="btn btn-danger btn-md">Delete Invoice</a>
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
                        <br><br>
                        
                     
                    </div>
                </div><br><br>



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

                  <div class="row">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3 style="float:left">New Payments</h3><br><br><br>

                            {!! Form::open(array('route' => array('edit_no_draft_invoice_path', $invoice_id))) !!}        
                                <input type="hidden" class="form-control" name="invoice_id" value="{{ $invoice_id }}">
                                <label>Payment Value</label><br>
                                <input type="text" class="form-control" name="payment_value" value=""><br>
                                <div class="form-group">
                                    <button name="add_payment_submit" type="submit" class="btn btn-primary btn-md">Add payment</button>
                                </div>
                            {!! Form::close() !!}

                            {!! Form::open(array('route' => array('edit_no_draft_invoice_path', $invoice_id))) !!}     
                                <input type="hidden" class="form-control" name="invoice_id" value="{{ $invoice_id }}">
                                <div class="form-group">
                                    <button name="close_invoice_submit" type="submit" class="btn btn-success btn-md">Close Invoice</button> 
                                </div>
                            {!! Form::close() !!}   

                            {!! Form::open(array('route' => array('edit_no_draft_invoice_path', $invoice_id))) !!}     
                                <input type="hidden" class="form-control" name="invoice_id" value="{{ $invoice_id }}">
                                <div class="form-group">
                                    <button name="cancel_invoice_submit" type="submit" class="btn btn-danger btn-md">Cancel Invoice</button> 
                                </div>
                            {!! Form::close() !!}   
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
