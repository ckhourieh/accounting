@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-10">
        <h1 class="page-header">
            Invoices
        </h1>
    </div>
    <div class="col-md-2">
        <a href="{{ route('add_invoice_path') }}" class="btn btn-default btn-lg pull-right">Add Invoice</a>
    </div>
</div>

@if(Session::has('flash_message'))
    <div class="alert alert-success"><em> {!! session('flash_message') !!}</em></div>
@endif

<div class="row">
    <!-- Advanced Tables -->
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="clients" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Due Date</th>
                            <th>Creation Date</th>
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
                        @foreach($invoicesList as $i)             
                            <tr <?php if(($i->due_date < date("Y-m-d")) && ($i->status_id == '4')) echo 'class="danger"';?>>
                                <td><a href="{{ route('view_invoice_details_path', $i->invoice_id) }}">{{ $i->invoice_id }}</a></td>
                                <td>{{ $i->due_date }}</td>
                                <td>{{ $i->created_at }}</td>
                                <td><a href="{{ route('view_client_details_path', $i->client_id) }}">{{ $i->name }}</a></td>
                                <td>
                                    @if($i->next_payment != '0000-00-00')
                                    {{ $i->next_payment }}
                                    @endif
                                </td>
                               
                                <td>$ {{ number_format($i->amount, 2, '.', ' ') }}</td>
                                <td style="color:{{$i->color_code}}"><b>{{ $i->status }}</b></td>
                                <td style="color:#5cb85c">
                                    @if($i->paid)
                                        {{ number_format($i->paid, 2, '.', ' ') }}
                                    @endif
                                </td>
                                <td style="color:#d9534f">
                                    @if($i->amount - $i->paid)
                                        <?php $r=($i->amount - $i->paid);  ?>
                                        {{ number_format($r, 2, '.', ' ') }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('print_invoice_path', $i->invoice_id) }}" class="btnPrint"><i class="fa fa-print"></i></a>
                                    <a href="{{ route('download_invoice_path', $i->invoice_id) }}"><i class="fa fa-download"></i></a>
                                    <!-- if status is different from paid -->
                                    @if($i->status_id != 3)
                                        <a href="{{ route('edit_invoice_path', $i->invoice_id) }}"><i class="fa fa-pencil-square-o"></i></a>
                                    @endif

                                    @if($i->status_id == '6')    
                                        <a onclick="return confirm('Are you sure you want to send the invoice number {{$i->invoice_id}}?')" href="{{ route('send_invoice_path', $i->invoice_id) }}"><i class="fa fa-paper-plane-o"></i></a>
                                        <a onclick="return confirm('Are you sure you want to remove this invoice number {{$i->invoice_id}} from the list?');" href="{{ route('hide_invoice_path', $i->invoice_id) }}"><i class="fa fa-trash-o"></i></a>
                                    @endif      
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
    <!--End Advanced Tables -->
</div>

<!-- DATA TABLE -->
<link rel="stylesheet" type="text/css" href="/js/dataTables/dataTables.bootstrap.min.css">
<script src="/js/dataTables/jquery.dataTables.min.js"></script>
<script src="/js/dataTables/dataTables.bootstrap.min.js"></script>
<!-- PRINT PAGE -->
<script type="text/javascript" src="/js/jquery.printPage.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#clients').DataTable({
            "order": [[ 0, "desc" ]]
        });
        $(".btnPrint").printPage();
    });
</script>
@endsection
