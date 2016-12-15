@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            Edit Client : {{ $clientInfo[0]->name }}
        </h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                         {!! Form::open(array('route' =>array('edit_client_path', $client_id), 'files' => true)) !!}

                        <input type="hidden" class="form-control" id="source_id" name="source_id" value="{{ $clientInfo[0]->source_id }}">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="source_name" value="{{ $clientInfo[0]->name }}">
                            </div>

                            <div class="form-group">
                                <label>Logo</label><br/>
                                @if($clientInfo[0]->img == NULL)
                                    <img width="250px;" src="/images/client.jpg"/>
                                @else
                                    <img width="250px;" src="/images/clients/{{$clientInfo[0]->img}}"/>
                                @endif
                                <input type="file" name="client_img">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" name="source_desc">{{ $clientInfo[0]->desc }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="source_email" value="{{ $clientInfo[0]->email }}">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control" name="source_phone" value="{{ $clientInfo[0]->phone }}">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" rows="3" name="source_address">{{ $clientInfo[0]->address }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Owner</label>
                                <input type="text" class="form-control" name="source_owner" value="{{ $clientInfo[0]->owner }}">
                            </div>
                            <div class="form-group">
                                <label>Contact Name</label>
                                <input type="text" class="form-control" name="source_contact" value="{{ $clientInfo[0]->contact_name }}">
                            </div>
                            <div class="form-group">
                                <label>Accounting ID</label>
                                <input type="text" class="form-control" name="source_accounting" value="{{ $clientInfo[0]->accounting_id }}">
                            </div>
                            <div class="form-group">
                                <label>Follow up Date</label>
                                <input type="text" class="form-control" name="follow_up_date" value="{{ $clientInfo[0]->follow_up_date }}">
                            </div>
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea class="form-control" name="notes">{{ $clientInfo[0]->notes }}</textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">Edit Client</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<a href="{{ route('clients_path') }}" class="btn btn-default btn-lg">Go Back to Client's List</a>

@endsection
