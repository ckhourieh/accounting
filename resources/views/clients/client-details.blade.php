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
            @foreach($clientInfo as $ci)
                <div class="panel-heading">Name</div>
                <div class="panel-body">{{ $ci->name }}</div>
                <div class="panel-heading">Description</div>
                <div class="panel-body">{{ $ci->desc }}</div>
                <div class="panel-heading">Email</div>
                <div class="panel-body">{{ $ci->email }}</div>
                <div class="panel-heading">Phone</div>
                <div class="panel-body">{{ $ci->phone }}</div>
                <div class="panel-heading">Address</div>
                <div class="panel-body">{{ $ci->address }}</div>
                <div class="panel-heading">Owner</div>
                <div class="panel-body">{{ $ci->owner }}</div>
                <div class="panel-heading">Contact Name</div>
                <div class="panel-body">{{ $ci->contact_name }}</div>
                <div class="panel-heading">Accounting ID</div>
                <div class="panel-body">{{ $ci->accounting_id }}</div>
                @if($dueInvoicesList)
                <div class="panel-heading">Total Amount Due</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Due Date</th>
                                    <th>Client Name</th>
                                    <th>Next Payments</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Paid</th>
                                    <th>Remaining</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dueInvoicesList as $i)             
                                    <tr>
                                        <td><a href="{{ route('view_invoice_details_path', $i->invoice_id) }}">{{ $i->invoice_id }}</a></td>
                                        <td>{{ $i->due_date }}</td>
                                        <td>{{ $i->name }}</td>
                                        <td>
                                            @if($i->next_payment != '0000-00-00')
                                            {{ $i->next_payment }}
                                            @endif
                                        </td>
                                        <td>$ {{ $i->amount }}.00</td>
                                        <td>{{ $i->status }}</td>
                                        <td>$ {{ $i->paid }}.00</td>
                                        <td>$ {{ $i->amount - $i->paid }}.00</td>
                                        <td>
                                            <a href="{{ route('print_invoice_path', $i->invoice_id) }}" class="btnPrint"><i class="fa fa-print"></i></a>
                                            <a href="javascript:DownloadInvoice();"><i class="fa fa-download"></i></a>
                                            <a href="{{ route('edit_invoice_path', $i->invoice_id) }}"><i class="fa fa-pencil-square-o"></i></a>
                                            <a href="{{ route('hide_invoice_path', $i->invoice_id) }}"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @if($payedInvoicesList)
                <div class="panel-heading">Total Income</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Due Date</th>
                                    <th>Client Name</th>
                                    <th>Next Payments</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Paid</th>
                                    <th>Remaining</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payedInvoicesList as $i)             
                                    <tr>
                                        <td><a href="{{ route('view_invoice_details_path', $i->invoice_id) }}">{{ $i->invoice_id }}</a></td>
                                        <td>{{ $i->due_date }}</td>
                                        <td>{{ $i->name }}</td>
                                        <td>
                                            @if($i->next_payment != '0000-00-00')
                                            {{ $i->next_payment }}
                                            @endif
                                        </td>
                                        <td>$ {{ $i->amount }}.00</td>
                                        <td>{{ $i->status }}</td>
                                        <td>$ {{ $i->paid }}.00</td>
                                        <td>$ {{ $i->amount - $i->paid }}.00</td>
                                        <td>
                                            <a href="{{ route('print_invoice_path', $i->invoice_id) }}" class="btnPrint"><i class="fa fa-print"></i></a>
                                            <a href="javascript:DownloadInvoice();"><i class="fa fa-download"></i></a>
                                            <a href="{{ route('edit_invoice_path', $i->invoice_id) }}"><i class="fa fa-pencil-square-o"></i></a>
                                            <a href="{{ route('hide_invoice_path', $i->invoice_id) }}"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                <div class="panel-heading">Timeline</div>
                <div class="panel-body">
                    <div id="morris-bar-chart"></div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- /.row (nested) -->


<a href="{{ route('clients_path') }}" class="btn btn-default btn-lg">Go Back to Client's List</a>

<!-- DATA TABLE -->
<link rel="stylesheet" type="text/css" href="/js/dataTables/dataTables.bootstrap.min.css">
<script src="/js/dataTables/jquery.dataTables.min.js"></script>
<script src="/js/dataTables/dataTables.bootstrap.min.js"></script>

<!-- PRINT PAGE -->
<script type="text/javascript" src="/js/jquery.printPage.js"></script>

<script type="text/javascript" src="/js/html2canvas.js"></script>
<script type="text/javascript" src="/js/download.js"></script>

<!-- Morris Chart Js -->
<script src="/js/morris/raphael.min.js"></script>
<script src="/js/morris/morris.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.table').DataTable({
            "order": [[ 0, "desc" ]]
        });
        $(".btnPrint").printPage();

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

    });
</script>

@endsection
