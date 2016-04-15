@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Webneoo Team
        </h1>
    </div>
    
</div>



<div class="row placeholders">
  
  @foreach($teamInfo as $t)
    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 placeholder">
      <a href="{{ route('profile_details_path', $t->id) }}"> <img alt="User Pic" src="images/team/{{$t->img}}" class="img-circle img-responsive"> </a>
      <h4><b>{{ $t->firstname }} {{$t->lastname}}</b></h4>
      <span class="text-muted">{{ $t->position }}</span>
    </div>
  @endforeach
  
</div>


@endsection
