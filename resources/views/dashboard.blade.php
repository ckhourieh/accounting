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

    <div class="col-md-3">
        <div class="panel panel-primary text-center no-boder bg-color-green green">
            <div class="panel-left pull-left green">
                <i class="fa fa-bar-chart-o fa-5x"></i>
            </div>
            <div class="panel-right pull-right">
                <h3>{{ $socialMediaTotalDue[0]->total }}</h3>
               <strong> Total Due Social Media </strong>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-primary text-center no-boder bg-color-blue blue">
            <div class="panel-left pull-left blue">
                <i class="fa fa-shopping-cart fa-5x"></i>
            </div>
            <div class="panel-right pull-right">
                <h3>{{ $webDevelopmentTotalDue[0]->total }}</h3>
                <strong> Total Due Web Development </strong>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-primary text-center no-boder bg-color-red red">
            <div class="panel-left pull-left red">
                <i class="fa fa fa-comments fa-5x"></i>
            </div>
            <div class="panel-right pull-right">
                <h3>{{ $emailsHostingTotalDue[0]->total }}</h3>
                <strong> Total Due Emails & Hosting </strong>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-primary text-center no-boder bg-color-brown brown">
            <div class="panel-left pull-left brown">
                <i class="fa fa-users fa-5x"></i>
            </div>
            <div class="panel-right pull-right">
                <h3>{{ $socialMediaTotalDue[0]->total + $webDevelopmentTotalDue[0]->total + $emailsHostingTotalDue[0]->total }}</h3>
                <strong> Total Due </strong>
            </div>
        </div>
    </div>

</div>


<div class="row">

    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Statistics of the year
            </div>
            <div class="panel-body">
                <div id="bar-chart-all"></div>
                <div id="bar-chart-profit"></div>
                <div id="bar-chart-expenses"></div>
                <div id="bar-chart-income"></div>
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
(function ($) {

    $('#bar-chart-profit').hide();
    $('#bar-chart-expenses').hide();
    $('#bar-chart-income').hide();

    $('#profit').click(function(){
        $('#bar-chart-all').hide();
        $('#bar-chart-profit').show();
        $('#bar-chart-expenses').hide();
        $('#bar-chart-income').hide();
    });

    $('#expenses').click(function(){
        $('#bar-chart-all').hide();
        $('#bar-chart-profit').hide();
        $('#bar-chart-income').hide();
        $('#bar-chart-expenses').show();
    });

    $('#income').click(function(){
        $('#bar-chart-all').hide();
        $('#bar-chart-profit').hide();
        $('#bar-chart-expenses').hide();
        $('#bar-chart-income').show();
    });

    $('#all').click(function(){
        $('#bar-chart-profit').hide();
        $('#bar-chart-expenses').hide();
        $('#bar-chart-income').hide();
        $('#bar-chart-all').show();
    });

    /* MORRIS BAR CHART
    -----------------------------------------*/
    Morris.Bar({
        element: 'bar-chart-all',
        data: [{
            y: 'JAN',
            a: 100,
            b: 90,
            c: 10
        }, {
            y: 'FEB',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'MAR',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'APR',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'MAY',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'JUN',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'JUL',
            a: 100,
            b: 90,
            c: 10
        }, {
            y: 'AUG',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'SEP',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'OCT',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'NOV',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'DEC',
            a: 100,
            b: 90,
            c: 10
        }],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        labels: ['Income', 'Expenses', 'Profit'],
        barColors: ['#5cb85c','#d9534f', '#007cc2', '#A8E9DC'],
        hideHover: 'auto',
        resize: true
    });

    Morris.Bar({
        element: 'bar-chart-profit',
        data: [{
            y: 'JAN',
            a: 100,
            b: 90,
            c: 10
        }, {
            y: 'FEB',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'MAR',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'APR',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'MAY',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'JUN',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'JUL',
            a: 100,
            b: 90,
            c: 10
        }, {
            y: 'AUG',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'SEP',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'OCT',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'NOV',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'DEC',
            a: 100,
            b: 90,
            c: 10
        }],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        labels: ['Income', 'Expenses', 'Profit'],
        barColors: ['#fff','#fff', '#007cc2', '#A8E9DC'],
        hideHover: 'auto',
        resize: true
    });

    Morris.Bar({
        element: 'bar-chart-expenses',
        data: [{
            y: 'JAN',
            a: 100,
            b: 90,
            c: 10
        }, {
            y: 'FEB',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'MAR',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'APR',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'MAY',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'JUN',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'JUL',
            a: 100,
            b: 90,
            c: 10
        }, {
            y: 'AUG',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'SEP',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'OCT',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'NOV',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'DEC',
            a: 100,
            b: 90,
            c: 10
        }],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        labels: ['Income', 'Expenses', 'Profit'],
        barColors: ['#fff','#d9534f', '#fff', '#A8E9DC'],
        hideHover: 'auto',
        resize: true
    });

    Morris.Bar({
        element: 'bar-chart-income',
        data: [{
            y: 'JAN',
            a: 100,
            b: 90,
            c: 10
        }, {
            y: 'FEB',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'MAR',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'APR',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'MAY',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'JUN',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'JUL',
            a: 100,
            b: 90,
            c: 10
        }, {
            y: 'AUG',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'SEP',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'OCT',
            a: 50,
            b: 40,
            c: 10
        }, {
            y: 'NOV',
            a: 75,
            b: 65,
            c: 10
        }, {
            y: 'DEC',
            a: 100,
            b: 90,
            c: 10
        }],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        labels: ['Income', 'Expenses', 'Profit'],
        barColors: ['#5cb85c','#fff', '#fff', '#A8E9DC'],
        hideHover: 'auto',
        resize: true
    });


}(jQuery));
</script>
@endsection
