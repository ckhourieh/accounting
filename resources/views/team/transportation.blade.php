@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <img class="img-circle" src="images/team/{{ $user_info[0]->img}}" style="width:70px;">
            Add transportation for <b> {{$user_info[0]->firstname}} {{$user_info[0]->lastname}} </b>
        </h1>
    </div>
</div>

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(Session::has('flash_message'))
    <div class="alert alert-success"><em> {!! session('flash_message') !!}</em></div>
@endif


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                          {!! Form::open(array('route' => array('add_transportation_path', $user_info[0]->id))) !!} 
                          <div class="row"></div>

                                <div class="form-group col-lg-6">
                                    <label>Date</label>
                                    <div class='input-group date' id='datetimepicker1'>
                                         {{ Form::text('transport_date', date('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Enter the transport date'])  }}
                                         <span class="input-group-addon">
                                           <span class="glyphicon glyphicon-calendar"></span>
                                         </span>
                                     </div>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label>Place</label>
                                    <input type="text" class="form-control" name="transport_place" placeholder="Enter the transport place" value="{{  Request::old('transport_place') }}">
                                </div>
                               
                                <div class="form-group col-lg-6">
                                    <label>Reason</label>
                                    <input type="text" class="form-control" name="transport_reason" placeholder="Enter the transport reason" value="{{  Request::old('transport_reason') }}">
                                </div>
                                
                               

                                <div class="form-group col-lg-6">
                                    <label>Price</label>
                                     <div class="form-group input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control" name="transport_price" placeholder="Enter the transport price" value="{{  Request::old('transport_price') }}">
                                    </div>
                                </div>

                                <div class="form-group col-lg-6">
                                    <button type="submit" class="btn btn-success btn-lg">Add transportation</button>
                                    <a href="{{ route('profile_details_path', $user_info[0]->id) }}" class="btn btn-primary btn-lg">Back to {{ $user_info[0]->firstname}} {{ $user_info[0]->lastname}} profile</a>

                                </div>


                            </div> <!-- end row -->

                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.row (nested) -->
            

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel panel-info">
                                
                            <div class="panel-heading">
                              <h3 class="panel-title" style="position:relative; top:-6px; font-weight:bold;"> Tranportation history of {{ $user_info[0]->firstname}} {{ $user_info[0]->lastname }} - {{ date('M Y') }}</h3>
                            </div>

                            <div class="table-responsive">
                                <div class="col-lg-12">
                                    <br/>
                                    <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th><b>#</b></th>
                                                <th><b>Date</b></th>
                                                <th><b>Place</b></th>
                                                <th><b>Reason</b></th>
                                                <th><b>Price</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transportation_info as $t)             
                                                <tr>
                                                    <td>{{ $t->transport_id }}</td>
                                                    <td>{{ $t->transport_date }}</td>
                                                    <td>{{ $t->place }}</td>
                                                    <td>{{ $t->reason }}</td>
                                                    <td>{{ $t->price }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<a href="{{ route('clients_path') }}" class="btn btn-default btn-lg">Go Back to Client's List</a>


<!-- DATA TABLE -->
<link rel="stylesheet" type="text/css" href="/js/dataTables/dataTables.bootstrap.min.css">
<script src="/js/dataTables/jquery.dataTables.min.js"></script>
<script src="/js/dataTables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">

    $(function () {
      $(".table").DataTable({
        "order": [[ 0, "desc" ]]
    });
 });


</script>




<!-- DATE PICKER -->
<script src="../js/moment.js"></script>
 <script src="../js/datepicker.js"></script>
 <script type="text/javascript">
     $(function () {
         $('#datetimepicker1').datetimepicker({
             pickTime: false,
             format: 'YYYY-MM-DD'
         });
     });
 </script>

@endsection
