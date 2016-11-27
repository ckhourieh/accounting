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
            <div class="panel panel-info">
                    
                    <div class="panel-heading">
                      <h3 class="panel-title" style="position:relative; top:-6px; font-weight:bold;">{{ $clientInfo[0]->name }}</h3>
                    </div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-3 col-lg-3 " align="center"> 
                            @if($clientInfo[0]->img == NULL)
                            <img alt="User Pic" src="images/client.jpg" class="img-circle img-responsive"> 
                            @else
                            <img alt="User Pic" src="images/clients/{{$clientInfo[0]->img}}" class="img-responsive"> 
                            @endif
                        </div>
                        
                        <div class=" col-md-9 col-lg-9 "> 
                          <table class="table table-user-information">
                            <tbody>
                              <tr>
                                <td>Name:</td>
                                <td><b>{{ $clientInfo[0]->name }}</b></td>
                              </tr>

                              <tr>
                                <td>Description:</td>
                                <td><b>{{ $clientInfo[0]->desc }}</b></td>
                              </tr>

                              <tr>
                                <td>Email:</td>
                                <td><b><a href="mailto:{{ $clientInfo[0]->email }}">{{ $clientInfo[0]->email }}</a></b></td>
                              </tr>

                              <tr>
                                <td>Phone:</td>
                                <td><b>{{ $clientInfo[0]->phone }}</b></td>
                              </tr>

                              <tr>
                                <td>Address:</td>
                                <td><b>{{ $clientInfo[0]->address }}</b></td>
                              </tr>

                              <tr>
                                <td>Owner:</td>
                                <td><b>{{ $clientInfo[0]->owner }}</b></td>
                              </tr>

                              <tr>
                                <td>Contact Name:</td>
                                <td><b>{{ $clientInfo[0]->contact_name }}</b></td>
                              </tr>

                              <tr>
                                <td>Financial number:</td>
                                <td><b>{{ $clientInfo[0]->accounting_id }}</b></td>
                              </tr>

                            </tbody>
                          </table>
                          
                        </div>
                      </div>
                    </div>

                    <div class="panel-heading">Total Income</div>
                    <div class="panel-body">
                     <div class="table-responsive">
                         <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Invoice #</th>
                                    <th>Due Date</th>
                                    <th>Client Name</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Paid</th>
                                    <th>Remaining</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientInvoices as $i)             
                                    <tr>
                                        <td>{{ $i->invoice_id }}</td>
                                        <td><a href="{{ route('view_invoice_details_path', $i->invoice_id) }}">{{ $i->invoice_nb }}</a></td>
                                        <td>{{ $i->due_date }}</td>
                                        <td>{{ $i->name }}</td>
                                        <td>$ {{ $i->amount }}</td>
                                        <td>{{ $i->status }}</td>
                                        <td>$ {{ $i->paid }}</td>
                                        <td>$ {{ $i->amount - $i->paid }}</td>
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
                <div class="panel-heading">Timeline</div>
                <div class="panel-body">
                    <div id="morris-bar-chart"></div>
                </div>
          
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
        // I changed the selector of table to select all the tables except the first one

        $(".table:not(:first)").DataTable({
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
