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

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Tasks Panel
            </div>
            <div class="panel-body">
                <div class="list-group">

                    <a href="#" class="list-group-item">
                        <span class="badge">7 minutes ago</span>
                        <i class="fa fa-fw fa-comment"></i> Commented on a post
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">16 minutes ago</span>
                        <i class="fa fa-fw fa-truck"></i> Order 392 shipped
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">36 minutes ago</span>
                        <i class="fa fa-fw fa-globe"></i> Invoice 653 has paid
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">1 hour ago</span>
                        <i class="fa fa-fw fa-user"></i> A new user has been added
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">1.23 hour ago</span>
                        <i class="fa fa-fw fa-user"></i> A new user has added
                    </a>
                    <a href="#" class="list-group-item">
                        <span class="badge">yesterday</span>
                        <i class="fa fa-fw fa-globe"></i> Saved the world
                    </a>
                </div>
                <div class="text-right">
                    <a href="#">More Tasks <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="col-md-6">
            <div class="panel panel-primary text-center no-boder bg-color-green green">
                <div class="panel-left pull-left green">
                    <i class="fa fa-bar-chart-o fa-5x"></i>
                </div>
                <div class="panel-right pull-right">
                    <h3>8,457</h3>
                   <strong> Clients </strong>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary text-center no-boder bg-color-blue blue">
                <div class="panel-left pull-left blue">
                    <i class="fa fa-shopping-cart fa-5x"></i>
                </div>
                <div class="panel-right pull-right">
                    <h3>52,160 </h3>
                    <strong> Transactions </strong>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary text-center no-boder bg-color-red red">
                <div class="panel-left pull-left red">
                    <i class="fa fa fa-comments fa-5x"></i>
                </div>
                <div class="panel-right pull-right">
                    <h3>25,823 </h3>
                    <strong> Invoices </strong>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary text-center no-boder bg-color-brown brown">
                <div class="panel-left pull-left brown">
                    <i class="fa fa-users fa-5x"></i>
                </div>
                <div class="panel-right pull-right">
                    <h3>26,337 </h3>
                    <strong> Bills </strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Progress
        </div>
        <div class="panel-body">
            <div id="morris-line-chart"></div>
        </div>
    </div>  
    </div>      
</div> 

<div class="row">
    <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Bar Chart Example
            </div>
            <div class="panel-body">
                <div id="morris-bar-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Donut Chart Example
            </div>
            <div class="panel-body">
                <div id="morris-donut-chart"></div>
            </div>
        </div>
    </div>

</div>
<div class="row">
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Area Chart
        </div>
        <div class="panel-body">
            <div id="morris-area-chart"></div>
        </div>
    </div>  
    </div>      
</div>  
<!-- /. ROW  -->

<!-- Morris Chart Js -->
<script src="/js/morris/raphael.min.js"></script>
<script src="/js/morris/morris.min.js"></script>

<script type="text/javascript">
(function ($) {

    /* MORRIS BAR CHART
    -----------------------------------------*/
    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            y: '2006',
            a: 100,
            b: 90
        }, {
            y: '2007',
            a: 75,
            b: 65
        }, {
            y: '2008',
            a: 50,
            b: 40
        }, {
            y: '2009',
            a: 75,
            b: 65
        }, {
            y: '2010',
            a: 50,
            b: 40
        }, {
            y: '2011',
            a: 75,
            b: 65
        }, {
            y: '2012',
            a: 100,
            b: 90
        }],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],
         barColors: [
    '#A6A6A6','#1cc09f',
    '#A8E9DC' 
    ],
        hideHover: 'auto',
        resize: true
    });



    /* MORRIS DONUT CHART
    ----------------------------------------*/
    Morris.Donut({
        element: 'morris-donut-chart',
        data: [{
            label: "Download Sales",
            value: 12
        }, {
            label: "In-Store Sales",
            value: 30
        }, {
            label: "Mail-Order Sales",
            value: 20
        }],
           colors: [
    '#A6A6A6','#1cc09f',
    '#A8E9DC' 
    ],
        resize: true
    });

    /* MORRIS AREA CHART
    ----------------------------------------*/

    Morris.Area({
        element: 'morris-area-chart',
        data: [{
            period: '2010 Q1',
            iphone: 2666,
            ipad: null,
            itouch: 2647
        }, {
            period: '2010 Q2',
            iphone: 2778,
            ipad: 2294,
            itouch: 2441
        }, {
            period: '2010 Q3',
            iphone: 4912,
            ipad: 1969,
            itouch: 2501
        }, {
            period: '2010 Q4',
            iphone: 3767,
            ipad: 3597,
            itouch: 5689
        }, {
            period: '2011 Q1',
            iphone: 6810,
            ipad: 1914,
            itouch: 2293
        }, {
            period: '2011 Q2',
            iphone: 5670,
            ipad: 4293,
            itouch: 1881
        }, {
            period: '2011 Q3',
            iphone: 4820,
            ipad: 3795,
            itouch: 1588
        }, {
            period: '2011 Q4',
            iphone: 15073,
            ipad: 5967,
            itouch: 5175
        }, {
            period: '2012 Q1',
            iphone: 10687,
            ipad: 4460,
            itouch: 2028
        }, {
            period: '2012 Q2',
            iphone: 8432,
            ipad: 5713,
            itouch: 1791
        }],
        xkey: 'period',
        ykeys: ['iphone', 'ipad', 'itouch'],
        labels: ['iPhone', 'iPad', 'iPod Touch'],
        pointSize: 2,
        hideHover: 'auto',
          pointFillColors:['#ffffff'],
          pointStrokeColors: ['black'],
          lineColors:['#A6A6A6','#1cc09f'],
        resize: true
    });

    /* MORRIS LINE CHART
    ----------------------------------------*/
    Morris.Line({
        element: 'morris-line-chart',
        data: [
              { y: '2014', a: 50, b: 90},
              { y: '2015', a: 165,  b: 185},
              { y: '2016', a: 150,  b: 130},
              { y: '2017', a: 175,  b: 160},
              { y: '2018', a: 80,  b: 65},
              { y: '2019', a: 90,  b: 70},
              { y: '2020', a: 100, b: 125},
              { y: '2021', a: 155, b: 175},
              { y: '2022', a: 80, b: 85},
              { y: '2023', a: 145, b: 155},
              { y: '2024', a: 160, b: 195}
        ],

         
    xkey: 'y',
    ykeys: ['a', 'b'],
    labels: ['Total Income', 'Total Outcome'],
    fillOpacity: 0.6,
    hideHover: 'auto',
    behaveLikeLine: true,
    resize: true,
    pointFillColors:['#ffffff'],
    pointStrokeColors: ['black'],
    lineColors:['gray','#1cc09f']

    });


}(jQuery));
</script>
@endsection
