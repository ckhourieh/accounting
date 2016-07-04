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

                          {!! Form::open(array('route' => array('preview_salary_path', $user_info[0]->id, $selected_month, $selected_year))) !!} 
                   
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
                                    <button name="preview_salary" type="submit" class="btn btn-success btn-lg">Preview Salary</button>
                                    <a href="{{ route('profile_details_path', $user_info[0]->id) }}" class="btn btn-primary btn-lg">Back to {{ $user_info[0]->firstname}} {{ $user_info[0]->lastname}} profile</a>
                                </div>


                            </div> <!-- end row -->

                        {!! Form::close() !!}
                    </div>
                </div>
  
            </div>
            <!-- /.panel-body -->

            @if(isset($total_amount))

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel panel-info">
                                
                                <div class="panel-heading">
                                  <h3 class="panel-title" style="position:relative; top:-6px; font-weight:bold;">{{ $user_info[0]->firstname}} {{ $user_info[0]->lastname }} - Salary for the month of {{date("F  Y", strtotime($month)) }} </h3>
                                </div>
                                <div class="panel-body">
                                  <div class="row">
                                    <div class="col-md-3 col-lg-3 " align="center"> 
                                        @if($user_info[0]->img == NULL)
                                        <img alt="User Pic" src="images/client.jpg" class="img-circle img-responsive"> 
                                        @else
                                        <img alt="User Pic" src="images/team/{{$user_info[0]->img}}" class="img-circle img-responsive"> 
                                        @endif
                                    </div>
                                    
                                    <div class=" col-md-9 col-lg-9 "> 
                                      <table class="table table-user-information">
                                        <tbody>
                                          <tr>
                                            <td>Name:</td>
                                            <td><b>{{ $user_info[0]->firstname}} {{ $user_info[0]->lastname }}</b></td>
                                          </tr>

                                          <tr>
                                            <td>Base salary:</td>
                                            <td style="color:#5cb85c"><b>${{ $base_salary_amount }}</b></td>
                                          </tr>

                                          <tr>
                                            <td>Days off:</td>
                                            <td style="color:#d43f3a"><b>${{ $days_off_amount }}</b></td>
                                          </tr>

                                          <tr>
                                            <td>Bonus:</td>
                                            <td style="color:#5cb85c"><b>${{ $bonus_amount }}</b></td>
                                          </tr>

                                          <tr>
                                            <td>Transportation:</td>
                                            <td style="color:#5cb85c"><b>${{ $transport_amount }}</b></td>
                                          </tr>

                                          @if($transport_details != NULL)
                                          <tr> 
                                            <td colspan="2">
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
                                                  @foreach($transport_details as $td)             
                                                      <tr>
                                                          <td>{{ $td->transport_id }}</td>
                                                          <td>{{ $td->transport_date }}</td>
                                                          <td>{{ $td->place }}</td>
                                                          <td>{{ $td->reason }}</td>
                                                          <td>{{ $td->price }}</td>
                                                      </tr>
                                                  @endforeach         
                                                  </tbody>
                                              </table>
                                            </td> 
                                          </tr>
                                          @endif

                                           <tr>
                                            <td><h2>Total amount</h2></td>
                                            <td><h2 style="color:#4cae4c">{{ number_format($total_amount,2,"."," ") }} USD</h2></td>
                                          </tr>

                                        </tbody>
                                      </table>

                                       {!! Form::open(array('route' => array('add_salary_path', $user_info[0]->id))) !!}

                                           <input type="hidden" name="transport_amount" value="{{ $transport_amount }}">
                                           <input type="hidden" name="days_off_amount" value="{{ $days_off_amount }}">
                                           <input type="hidden" name="bonus_amount" value="{{ $bonus_amount }}">
                                           <input type="hidden" name="base_salary_amount" value="{{ $base_salary_amount }}">
                                           <input type="hidden" name="total_amount" value="{{ $total_amount }}">

                                           <?php 

                                                $description = $user_info[0]->firstname."'s salary for the month of ".date('F  Y', strtotime($month)); 
                                           ?>

                                           <input type="hidden" name="description" value="{{ $description }}">
                                           <input type="hidden" name="transport_date" value="{{ $month }}">
                                            
                                            <div class="form-group col-lg-12">
                                                <button <?php if(!empty($salary_is_stored)) echo"disabled"; ?> type="submit" class="btn btn-success btn-lg">Store Salary</button>
                                                <a <?php if(empty($salary_is_stored)) echo"disabled onclick='return false';"; ?> class="btn btn-info btn-lg btnPrint" href="{{ route('print_salary_path', array($user_info[0]->id, $selected_month, $selected_year)) }}"> <i class="fa fa-print"></i> Print Salary</a>
                                            </div>

                                       {!! Form::close() !!}

                                    </div>
                                  </div>
                              </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row (nested) -->

            @endif



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
             format: 'YYYY-MM-DD',
             viewMode: "months",
             defaultDate: new Date()
         });
     });
 </script>
 <!-- PRINT PAGE -->
<script type="text/javascript" src="/js/jquery.printPage.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $(".btnPrint").printPage();
    });
</script>

@endsection
