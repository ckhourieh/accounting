@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Team member info
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel panel-info">
                    
                    <div class="panel-heading">
                      <h3 class="panel-title" style="position:relative; top:-6px; font-weight:bold;">{{ $user_info[0]->firstname}} {{ $user_info[0]->lastname }}</h3>
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
                                <td>Position:</td>
                                <td><b>{{ $user_info[0]->position }}</b></td>
                              </tr>

                              <tr>
                                <td>Birth date:</td>
                                <td><b>{{ $user_info[0]->birthdate }}</b></td>
                              </tr>

                              <tr>
                                <td>Email:</td>
                                <td><b><a href="mailto:{{ $user_info[0]->email }}">{{ $user_info[0]->email }}</a></b></td>
                              </tr>

                              <tr>
                                <td>Phone:</td>
                                <td><b>{{ $user_info[0]->phone }}</b></td>
                              </tr>

                              <tr>
                                <td>Emergency contact:</td>
                                <td><b>{{ $user_info[0]->emergency_contact }}</b></td>
                              </tr>

                              <tr>
                                <td>Emergency phone:</td>
                                <td><b>{{ $user_info[0]->emergency_phone }}</b></td>
                              </tr>

                              <tr>
                                <td>Address:</td>
                                <td><b>{{ $user_info[0]->address }}</b></td>
                              </tr>

                              <tr>
                                <td>Base Salary:</td>
                                <td><b>${{ $user_info[0]->base_salary }}</b></td>
                              </tr>

                              <tr>
                                <td>Joined webneoo on:</td>
                                <td><b>{{ $user_info[0]->joined_webneoo }}</b></td>
                              </tr>

                              <tr>
                                <td>Bank Account Number:</td>
                                <td><b>{{ $user_info[0]->bank_account }}</b></td>
                              </tr>

                              <tr>
                                <td>Role:</td>
                                <td><b>{{ $user_info[0]->role_desc }}</b></td>
                              </tr>

                              <tr>
                                <td>Active:</td>
                                <td>
                                <b>
                                    @if($user_info[0]->active == 1)
                                        <?php echo "YES" ?>
                                    @elseif($user_info[0]->active == 0)
                                         <?php echo "NO" ?>
                                    @endif
                                </b>
                                </td>
                              </tr>

                            </tbody>
                          </table>

                          <a href="{{ route('salary_path', $user_info[0]->id) }}" class="btn btn-success btn-lg"><i class="fa fa-money"></i> Salary</a>
                         <a  style="margin-left:50px;" href="{{ route('transportation_path', $user_info[0]->id) }}" class="btn btn-info btn-lg"><i class="fa fa-car"></i> Transportation</a>
                        </div>
                      </div>
                  </div>
            </div>
        </div>
    </div>

</div>
<!-- /.row (nested) -->


<a href="{{ route('team_path') }}" class="btn btn-primary btn-lg">Go Back to Team List</a>



@endsection
