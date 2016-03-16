@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Timeline
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Income / Outcome for : {{ $supplierInfo[0]->name }}
                </div>
                <div class="panel-body">
                    <div id="morris-bar-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row (nested) -->

<a href="{{ route('suppliers_path') }}" class="btn btn-default btn-lg">Go Back to Supplier's List</a>


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
            y: 'JAN',
            a: 100,
            b: 90
        }, {
            y: 'FEB',
            a: 75,
            b: 65
        }, {
            y: 'MAR',
            a: 50,
            b: 40
        }, {
            y: 'APR',
            a: 75,
            b: 65
        }, {
            y: 'MAY',
            a: 50,
            b: 40
        }, {
            y: 'JUN',
            a: 75,
            b: 65
        }, {
            y: 'JUL',
            a: 100,
            b: 90
        }, {
            y: 'AUG',
            a: 50,
            b: 40
        }, {
            y: 'SEP',
            a: 75,
            b: 65
        }, {
            y: 'OCT',
            a: 50,
            b: 40
        }, {
            y: 'NOV',
            a: 75,
            b: 65
        }, {
            y: 'DEC',
            a: 100,
            b: 90
        }],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Income', 'Outcome'],
         barColors: [
    '#5cb85c','#d9534f',
    '#A8E9DC' 
    ],
        hideHover: 'auto',
        resize: true
    });

}(jQuery));
</script>

@endsection