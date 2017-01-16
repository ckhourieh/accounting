@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Transaction Details
        </h1>
    </div>
</div>



<div class="row">

    <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title" style="position:relative; top:-6px; font-weight:bold;">Transaction # {{ $transactionInfo[0]->transaction_id }}</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center"> 
                    @if($transactionInfo[0]->img == NULL)
                        @if($transactionInfo[0]->type_id == 1)
                        <img src="images/client.jpg" class="img-circle img-responsive">
                        @else
                        <img src="images/supplier.png" class="img-circle img-responsive">
                        @endif
                    @else
                        @if($transactionInfo[0]->type_id == 1)
                        <img src="images/clients/{{$transactionInfo[0]->img}}" class="img-responsive"> 
                        @else
                        <img src="images/suppliers/{{$transactionInfo[0]->img}}" class="img-responsive"> 
                        @endif
                    @endif
                </div>
                
               
                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>Invoice ID:</td>
                        <td><b>{{$transactionInfo[0]->invoice_id}}</b></td>
                      </tr>

                      <tr>
                        <td>Invoice number:</td>
                        <td><b>{{$transactionInfo[0]->invoice_nb}}</b></td>
                      </tr>

                      <tr>
                        <td>Transaction date:</td>
                        <td><b>{{$transactionInfo[0]->date}}</b></td>
                      </tr>

                      <tr>
                        <td>Contact name:</td>
                        <td><b>{{$transactionInfo[0]->contact_name}}</b></td>
                      </tr>

                      <tr>
                        <td>Client / Supplier:</td>
                        <td><b>{{$transactionInfo[0]->source_name}}</b></td>
                      </tr>

                      <tr>
                        <td>Description:</td>
                        <td><b>{{$transactionInfo[0]->description}}</b></td>
                      </tr>

                      <tr>
                        <td>Amount:</td>
                        @if($transactionInfo[0]->type == 1)
                            <td style="color:#5cb85c">
                        @elseif($transactionInfo[0]->type == 0)
                            <td style="color:#d9534f">
                        @endif
                             <b>   $ {{ number_format($transactionInfo[0]->amount, 2, '.', ' ') }} </b>
                            </td>
                      </tr>

                      <tr>
                        <td>Transaction type:</td>
                            @if($transactionInfo[0]->type==0)
                                <td style="color:#d9534f"><b><i class="fa fa-arrow-up"></i> &nbsp&nbsp OUT </b></td>
                            @elseif($transactionInfo[0]->type==1)
                                <td style="color:#5cb85c"><b><i class="fa fa-arrow-down"></i> &nbsp&nbsp IN </b></td>
                            @endif
                      </tr>

                      <tr>
                        <td>Created at:</td>
                        <td><b>{{$transactionInfo[0]->created_at}}</b></td>
                      </tr>

                      <tr>
                        <td>Updated at:</td>
                        <td><b>{{$transactionInfo[0]->updated_at}}</b></td>
                      </tr>

                    </tbody>
                  </table>

                  <a href="{{ route('transactions_path') }}" class="btn btn-primary">Go Back to Transactions' List</a>
                  
                </div>
              </div>
            </div>

    </div>
    <!-- panel panel-info -->

</div>
<!-- row -->

@endsection
