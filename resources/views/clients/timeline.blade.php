@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Client Details
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Bar Chart
                </div>
                <div class="panel-body">
                    <div id="morris-bar-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row (nested) -->

<a href="{{ route('clients_path') }}" class="btn btn-default btn-lg">Go Back to Client's List</a>

<!-- Morris Chart Styles-->
<link href="/js/morris/morris.min.css" rel="stylesheet"/>
<!-- Morris Chart Js -->
<script src="/js/morris/raphael.min.js"></script>
<script src="/js/morris/morris.min.js"></script>
<script type="text/javascript">
(function ($) {
    "use strict";
    var mainApp = {

        initFunction: function () {

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
		}
	}
	 
}(jQuery));
</script>

@endsection