@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Dashboard
        </h1>
    </div>
</div>


<?php 
    echo 'Total Income: '.$data['total_income'][0]->total_income.'<br>';
    echo 'Total Expenses: '.$data['total_expenses'][0]->total_expenses.'<br>';
    echo 'Total Profit: '.$data['total_profit'][0]->total_profit.'<br>';
    echo 'Total Due Payments: '.$data['total_due_payments'][0]->total_due_payment.'<br>';
 ?>

<!-- /. ROW  -->

<div class="row">

    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Total Income</div>
            <div class="panel-heading">
                <label>Select your year</label>
                <input type="text" name="daterange" value="01/01/2015 - 01/31/2015" />

                
            </div>


            <div class="panel-body">
                <div id="morris-bar-chart"></div>
            </div>
        </div>
    </div>



    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Statistics of the year {{ date_format($actual_year,'Y') }}
            </div>
            <div class="panel-body">
                <div id="bar-chart-all"></div>
                <div id="bar-chart-profit"></div>
                <div id="bar-chart-expenses"></div>
                <div id="bar-chart-income"></div>
                <br/>
                <div class="col-xs-12">
                    <div class="btn btn-success" id="income">Income</div>
                    <div class="btn btn-danger" id="expenses">Expenses</div>
                    <div class="btn btn-primary" id="profit">Profit</div>
                    <div class="btn btn-default" id="all">All</div>
                </div>
            </div>

            <div class="row" style="margin-bottom:5px;">
                <div class="col-md-10 sous">
                    <h2>KPIs</h2>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-blue"><i class="fa fa-calendar-o"></i></span>
                        <div class="sm-st-info">
                            <span>{{ $data['total_income'][0]->total_income }}</span>
                            Total income
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-blue"><i class="fa fa-calendar"></i></span>
                        <div class="sm-st-info">
                            <span>{{ $data['total_expenses'][0]->total_expenses }}</span>
                            Total expenses 
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-blue"><i class="fa fa-calendar-plus-o"></i></span>
                        <div class="sm-st-info">
                            <span>{{ $data['total_profit'][0]->total_profit }}</span>
                            Total profit
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-blue"><i class="fa fa-calendar-check-o"></i></span>
                        <div class="sm-st-info">
                            <span>{{ $data['total_due_payments'][0]->total_due_payment }}</span>
                            Total due payments
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



</div>

<!-- Morris Chart Js -->
<script src="/js/morris/raphael.min.js"></script>
<script src="/js/morris/morris.min.js"></script>


<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 
<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<script type="text/javascript">

// convert the php data to json 
var incomes=<?=json_encode($data['income'])?>;
var expenses=<?=json_encode($data['expenses'])?>;
var profit=<?=json_encode($data['profit'])?>;

// month dictionary
var months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC' ];
var data= [];

var current_year = new Date().getFullYear(); //returns the current year
var month_num = ((current_year - 2015)+1)*12; // the number of months since 2015 till the actual year 
var year = 2015; // initialize the year to 2015
var m = 0; //initialize the month to zero 


// create an base array with values = zeros 
for(var i=0; i<month_num; i++)
{
  if(m==12) // if we have looped the 12 months, reset m to zero and increment a year
   {
    year++;
    m=0;
   } 
    
   data.push({  
                index:year+'_'+parseInt(m+1), 
                year: year, 
                month:months[m],
                axis_label: months[m]+' '+year, 
                income: 0, 
                expenses: 0, 
                profit: 0
            })  
   m++;
}

// loop the incomes array and replace the zero incomes by their values 
$.each( incomes, function( key, value ) {

   // find the index of this value in the data array 
   index = data.findIndex(item => item.index === value.this_year+'_'+value.this_month) 
   // set the income value of relative month
   data[index].income = value.total_income;
   
});     

// loop the expenses array and replace the zero expenses by their values 
$.each( expenses, function( key, value ) {

   // find the index of this value in the data array 
   index = data.findIndex(item => item.index === value.this_year+'_'+value.this_month) 
   // set the expenses value of relative month
   data[index].expenses = value.total_expenses;
   
});

// loop the profits array and replace the zero profits by their values 
$.each( profit, function( key, value ) {

   // find the index of this value in the data array 
   index = data.findIndex(item => item.index === value.this_year+'_'+value.this_month) 
   // set the profit value of relative month
   data[index].profit = value.total_profit;
   
});



$(function() {
    $('input[name="daterange"]').daterangepicker();
});


    


$(document).on('change', "#income_year", function() { 

    var year = $('#income_year').val();
    for(var m=0; m<12; m++)
    {
        data.push({ y: months[m], a: 100, b: 90})
    }


    console.log(data);
});  





/* Income chart bar
        -----------------------------------------*/
        Morris.Bar({
            element: 'morris-bar-chart',
            data: data,
            xkey: ['axis_label'],
            ykeys: ['income', 'expenses', 'profit'],
            labels: ['Income', 'Expenses', 'Profit'],
             barColors: [
        '#5cb85c','#d9534f', '#007cc2', 
        '#A8E9DC' 
        ],
            hideHover: 'auto',
            resize: true
        });


</script>
@endsection
