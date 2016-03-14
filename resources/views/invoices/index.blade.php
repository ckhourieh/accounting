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
                <table id="clients" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Due Date</th>
                            <th>Client Name</th>
                            <th>Title</th>
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
                            <tr>
                                <td><a href="{{ route('view_invoice_details_path', $i->invoice_id) }}">{{ $i->invoice_id }}</a></td>
                                <td>{{ $i->due_date }}</td>
                                <td>{{ $i->name }}</td>
                                <td>{{ $i->title }}</td>
                                <td></td>
                                <td>$ {{ $i->amount }}.00</td>
                                <td>{{ $i->status }}</td>
                                <td>$ {{ $i->paid }}.00</td>
                                <td>$ {{ $i->amount - $i->paid }}.00</td>
                                <td>
                                    <a href="{{ route('print_invoice_path', $i->invoice_id) }}" class="btnPrint"><i class="fa fa-print"></i></a>
                                    <a href="javascript:DownloadInvoice();"><i class="fa fa-download"></i></a>
                                    <a href="{{ route('edit_invoice_path', $i->invoice_id) }}"><i class="fa fa-pencil-square-o"></i></a>
                                    <a href="{{ route('hide_invoice_path', $i->id) }}"><i class="fa fa-trash-o"></i></a>
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

<script type="text/javascript" src="/js/html2canvas.js"></script>
<script type="text/javascript" src="/js/download.js"></script>

<script type="text/javascript">
    function DownloadInvoice() {
        window.location = "{{ route('print_invoice_path', $i->invoice_id) }}";
    }

    $(document).ready(function () {
        $('#clients').DataTable();
        $(".btnPrint").printPage();
    });
</script>
@endsection
