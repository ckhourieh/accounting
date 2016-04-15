@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <img class="img-circle" src="images/team/{{ $user_info[0]->img}}" style="width:70px;">
            Salary of <b> {{$user_info[0]->firstname}} {{$user_info[0]->lastname}} </b>
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
                          {!! Form::open(array('route' => array('preview_salary_path', $user_info[0]->id))) !!} 
                          <div class="row"></div>

                                <div class="form-group col-lg-3">
                                    <label>Date</label>
                                    <div class='input-group date' id='datetimepicker1'>
                                         {{ Form::text('transport_date', date('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Enter the salary date'])  }}
                                         <span class="input-group-addon">
                                           <span class="glyphicon glyphicon-calendar"></span>
                                         </span>
                                     </div>
                                </div>

                                <div class="form-group col-lg-3">
                                    <label>Days off</label>
                                    <input type="text" class="form-control" name="days_off" placeholder="Enter the fays off" value="{{  Request::old('days_off') }}">
                                </div>
                                                               
                            
                                <div class="form-group col-lg-3">
                                    <label>Bonus</label>
                                     <div class="form-group input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" class="form-control" name="bonus" placeholder="Enter the bonus" value="{{  Request::old('bonus') }}">
                                    </div>
                                </div>

                                <div class="form-group col-lg-12">
                                    <button type="submit" class="btn btn-success btn-lg">Preview Salary</button>
                                    <a href="{{ route('profile_details_path', $user_info[0]->id) }}" class="btn btn-primary btn-lg">Back to {{ $user_info[0]->firstname}} {{ $user_info[0]->lastname}} profile</a>
                                </div>


                            </div> <!-- end row -->

                        {!! Form::close() !!}
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



<!-- DATE PICKER -->
<script src="../js/moment.js"></script>
 <script src="../js/datepicker.js"></script>
 <script type="text/javascript">
     $(function () {
         $('#datetimepicker1').datetimepicker({
             pickTime: false,
             format: 'MM-DD',
             viewMode: "months"
         });
     });
 </script>

@endsection
