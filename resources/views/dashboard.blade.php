@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Dashboard
        </h1>
    </div>
</div>


<!-- /. ROW  -->

<div class="row">

    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Statistics of the year {{ $actual_year }}
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
        </div>
    </div>

</div>

<!-- Morris Chart Js -->
<script src="/js/morris/raphael.min.js"></script>
<script src="/js/morris/morris.min.js"></script>

<script type="text/javascript">

var tab_month=<?=json_encode($tab_month)?>;


// ------------------ TOTAL INCOME ---------------

var tab_income=<?=json_encode($tab_income)?>;

// construct the data table of the income
var income_data="[";
for(var a = 1; a<=12; a++)
{
    income_data+= "{y:'"+tab_month[a]+"',a:"+tab_income[a]+"},";
}

//remove the last coma from s
income_data=income_data.slice(0,-1); 

income_data+="]";


// ------------------ TOTAL EXPENSES ---------------

var tab_expenses =<?=json_encode($tab_expenses)?>;

// construct the data table of the expenses
var expenses_data="[";
for(var b = 1; b<=12; b++)
{
    expenses_data+= "{y:'"+tab_month[b]+"',a:"+tab_expenses[b]+"},";
}

//remove the last coma from s
expenses_data=expenses_data.slice(0,-1); 

expenses_data+="]";



// ------------------ TOTAL PROFIT ---------------

// construct the data table of the profit
var profit_data="[";
for(var c = 1; c<=12; c++)
{
    profit_data+= "{y:'"+tab_month[c]+"',a:"+(tab_income[c]-tab_expenses[c])+"},";
}

//remove the last coma from s
profit_data=profit_data.slice(0,-1); 

profit_data+="]";



// ------------------ TOTAL FOR ALL  ---------------

// construct the data table of the profit
var all_data="[";
for(var d = 1; d<=12; d++)
{
    all_data+= "{y:'"+tab_month[d]+"',a:"+tab_income[d]+",b:"+tab_expenses[d]+",c:"+(tab_income[d]-tab_expenses[d])+"},";
}

//remove the last coma from s
all_data=all_data.slice(0,-1); 

all_data+="]";




(function ($) {

    $('#bar-chart-profit').empty();
    $('#bar-chart-expenses').empty();
    $('#bar-chart-income').empty();

    $('#income').click(function(){

        $('#bar-chart-income').empty();
        $('#bar-chart-all').empty();
        $('#bar-chart-profit').empty();
        $('#bar-chart-expenses').empty();
        $('#bar-chart-income').show();
    
    Morris.Bar({
        element: 'bar-chart-income',
        data: eval(income_data),
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Income'],
        barColors: ['#5cb85c'],
        hideHover: 'auto',
        resize: true
    });


    });


    $('#expenses').click(function(){

        $('#bar-chart-expenses').empty();
        $('#bar-chart-all').empty();
        $('#bar-chart-profit').empty();
        $('#bar-chart-income').empty();
        $('#bar-chart-expenses').show();

        Morris.Bar({
            element: 'bar-chart-expenses',
            data: eval(expenses_data),
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Expenses'],
            barColors: ['#d9534f'],
            hideHover: 'auto',
            resize: true
        });
    });


     $('#profit').click(function(){

        $('#bar-chart-profit').empty();
        $('#bar-chart-all').empty();
        $('#bar-chart-profit').show();
        $('#bar-chart-expenses').empty();
        $('#bar-chart-income').empty();

        Morris.Bar({
            element: 'bar-chart-profit',
            data: eval(profit_data),
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Profit'],
            barColors: ['#007cc2'],
            hideHover: 'auto',
            resize: true
        });

    });


    $('#all').click(function(){
        
        $('#bar-chart-all').empty();
        $('#bar-chart-profit').empty();
        $('#bar-chart-expenses').empty();
        $('#bar-chart-income').empty();
        $('#bar-chart-all').show();


        /* MORRIS BAR CHART
        -----------------------------------------*/
        Morris.Bar({
            element: 'bar-chart-all',
            data: eval(all_data),
            xkey: 'y',
            ykeys: ['a', 'b', 'c'],
            labels: ['Income', 'Expenses', 'Profit'],
            barColors: ['#5cb85c','#d9534f', '#007cc2', '#A8E9DC'],
            hideHover: 'auto',
            resize: true
        });


    });



    /* MORRIS BAR CHART
    -----------------------------------------*/
    Morris.Bar({
        element: 'bar-chart-all',
        data: eval(all_data),
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        labels: ['Income', 'Expenses', 'Profit'],
        barColors: ['#5cb85c','#d9534f', '#007cc2', '#A8E9DC'],
        hideHover: 'auto',
        resize: true
    });




}(jQuery));

</script>
@endsection
