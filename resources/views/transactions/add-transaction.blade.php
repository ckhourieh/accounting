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
            Add Transaction
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
                        {!! Form::open(array('route' => 'add_transaction_path')) !!}
                            
                            <div class="form-group">
                                <label>Source (Client / Supplier)</label>
                                <select class="form-control" name="transaction_source">
                                    <option style="background:#999999; color:white" value="" disabled selected>Enter Transaction Client or Supplier</option>
                                    @foreach($all_sources as $a)
                                        <option @if (Request::old('transaction_source') == $a->source_id) selected="selected" @endif value="{{ $a->source_id }}">{{ $a->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                           
                            <div class="form-group">
                                <label>Amount</label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">$</span>
                                    <input id="amount_usd" type="text" class="form-control" name="transaction_amount" placeholder="Enter Transaction Amount" value="{{  Request::old('transaction_amount') }}">
                                    <span class="input-group-addon">.00</span>
                                </div>

                                <div class="form-group input-group">
                                    <span class="input-group-addon">LBP</span>
                                    <span id="amount_lbp" class="form-control">{{  Request::old('transaction_amount') *1500 }}</span>
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Transaction category</label>
                                <select class="form-control" name="transaction_source">
                                    <option style="background:#999999; color:white" value="" disabled selected>Select the category of the transaction</option>
                                    @foreach($category_list as $c)
                                        <option @if (Request::old('transaction_category_id') == $c->category_id) selected="selected" @endif value="{{ $c->category_id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" Placeholder="Description" name="transaction_description">{{  Request::old('transaction_description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Date</label>
                                <div class="form-group">
                                 <!-- View website http://eonasdan.github.io/bootstrap-datetimepicker/ -->
                                 <div class='input-group date' id='datetimepicker1'>
                                     {{ Form::text('transaction_date', date('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Date'])  }}
                                     <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                     </span>
                                 </div>
                               </div>
                            </div>

                            <div class="form-group">
                                <label>Type</label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label id="out" class="btn btn-default **active**">
                                        <input type="radio" name="transaction_type" id="transaction_type0" value="0">
                                    OUT 
                                    </label>
                                    <label id="in" class="btn btn-default">
                                        <input type="radio" name="transaction_type" id="transaction_type1" value="1">
                                    IN 
                                    </label>
                                </div>
                            </div>


                            <div id="invoice_id" class="form-group" style="display:none;">
                                <label>Invoice #</label>
                                <div class="form-group input-group">
                                    <input type="text" class="form-control" name="transaction_invoice" placeholder="Enter the invoice number">
                                </div>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Add Transaction</button>
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

<a href="{{ route('transactions_path') }}" class="btn btn-default btn-lg">Go Back to Transactions' List</a>



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
  $("#amount_usd").keyup(function(){
        // get the amount in USD
        var amount_usd = $("#amount_usd").val(); 
        // convert it to LBP
        var amount_lbp = amount_usd*1500;
        //display it in the LBP field 
        $("#amount_lbp").html(addCommas(amount_lbp)); 
        
        $("#amount_lbp").css("background-color", "#b5e0ad");
        // if no input set the background to white and the empty the field
        if(amount_usd == "")
        {
           $("#amount_lbp").css("background-color", "white"); 
           $("#amount_lbp").html('')
        }
    });


// show the invoice id section when clicking OUT
 $("#out").click(function(){
        $('#invoice_id').slideDown();
    });

 // hide the invoice id section when clicking IN
 $("#in").click(function(){
        $('#invoice_id').slideUp();
    });


  


 </script>

@endsection
