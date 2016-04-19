@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Supplier Details
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
                

        <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title" style="position:relative; top:-6px; font-weight:bold;">{{ $supplierInfo[0]->name }}</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center"> 
                    @if($supplierInfo[0]->img == NULL)
                        <img alt="User Pic" src="images/supplier.png" class="img-circle img-responsive"> 
                    @else
                        <img alt="User Pic" src="images/suppliers/{{$supplierInfo[0]->img}}" class="img-responsive"> 
                    @endif
                </div>
                
                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>Name:</td>
                        <td><b>{{ $supplierInfo[0]->name }}</b></td>
                      </tr>

                      <tr>
                        <td>Description:</td>
                        <td><b>{{ $supplierInfo[0]->desc }}</b></td>
                      </tr>

                      <tr>
                        <td>Email:</td>
                        <td><b><a href="mailto:{{ $supplierInfo[0]->email }}">{{ $supplierInfo[0]->email }}</a></b></td>
                      </tr>

                      <tr>
                        <td>Phone:</td>
                        <td><b>{{ $supplierInfo[0]->phone }}</b></td>
                      </tr>

                      <tr>
                        <td>Address:</td>
                        <td><b>{{ $supplierInfo[0]->address }}</b></td>
                      </tr>

                      <tr>
                        <td>Owner:</td>
                        <td><b>{{ $supplierInfo[0]->owner }}</b></td>
                      </tr>

                      <tr>
                        <td>Contact Name:</td>
                        <td><b>{{ $supplierInfo[0]->contact_name }}</b></td>
                      </tr>

                      <tr>
                        <td>Financial number:</td>
                        <td><b>{{ $supplierInfo[0]->accounting_id }}</b></td>
                      </tr>

                    </tbody>
                  </table>
                  
                </div>
              </div>
            </div>

    </div>
    <!-- panel panel-info -->

                @if($spentTransactionsList)
                <div class="panel-heading">Total Amount Spent</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Transaction #</th>
                                    <th>Date</th>
                                    <th>Supplier Name</th>
                                    <th>Amount</th>
                                    <th>Contact Person</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($spentTransactionsList as $i)             
                                    <tr>
                                        <td><a href="{{ route('view_transaction_details_path', $i->transaction_id) }}">{{ $i->transaction_id }}</a></td>
                                        <td>{{ $i->date }}</td>
                                        <td>{{ $i->supplier_name }}</td>
                                        <td>$ {{ $i->amount }}.00</td>
                                        <td>{{ $i->contact_name }}</td>
                                        <td>
                                            <a href="{{ route('hide_transaction_path', $i->transaction_id) }}"><i class="fa fa-trash-o"></i></a>
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
          
        </div>
    </div>
</div>
<!-- /.row (nested) -->


<a href="{{ route('suppliers_path') }}" class="btn btn-default btn-lg">Go Back to Supplier's List</a>

<!-- DATA TABLE -->
<link rel="stylesheet" type="text/css" href="/js/dataTables/dataTables.bootstrap.min.css">
<script src="/js/dataTables/jquery.dataTables.min.js"></script>
<script src="/js/dataTables/dataTables.bootstrap.min.js"></script>

<!-- Morris Chart Js -->
<script src="/js/morris/raphael.min.js"></script>
<script src="/js/morris/morris.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        // I changed the selector of table to select all the tables except the first one

         $(".table:not(:first)").DataTable({
            "order": [[ 0, "desc" ]]
        });

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
