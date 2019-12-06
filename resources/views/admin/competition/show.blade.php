@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Competition {{ $competition->id }}</div>
                <div class="card-body">

                    <a href="{{ url('/admin/competition') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                    <a href="{{ url('/admin/competition/' . $competition->id . '/edit') }}" title="Edit Competition"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                    {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['admin/competition', $competition->id],
                    'style' => 'display:inline'
                    ]) !!}
                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-sm',
                    'title' => 'Delete Competition',
                    'onclick'=>'return confirm("Confirm delete?")'
                    ))!!}
                    {!! Form::close() !!}
                    <br/>
                    <br/>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ID</th><td>{{ $competition->id }}</td>
                                </tr>
                                <tr>
                                    <th> Image </th>
                                    <td>
  <?php if (file_exists(public_path(App\Http\Controllers\Admin\CompetitionController::$_mediaBasePath . $competition->image))): ?>
 <img width="50" src="<?= url(App\Http\Controllers\Admin\CompetitionController::$_mediaBasePath . $competition->image) ?>">
        <?php else: ?>
                <!--<span>-</span>-->
 <img width="50" src="<?= url(App\Http\Controllers\Admin\CompetitionController::$_mediaBasePath . 'noimage.png') ?>">
        <?php endif; ?>    
                    </td>
                                </tr>
                                <tr>
                                    <th> Description </th><td> {{ $competition->description }} </td></tr><tr><th> Name </th><td> {{ $competition->name }} </td></tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                  <div class="card">
                                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive table-striped" width="100%" id="myTable">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Username</th>
                                    <th>Score</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php //if(sizeof($orderDetails)>0 || sizeof($user)>0){ ?>
                                 <?php foreach($orderDetails as $details){ ?>
                                
                                <tr>
                                    <td>{{$details->id}}</td>
                                    <td> <?php echo $user[0]['first_name']; ?></td>
                                    <td>{{$details->score}}</td>
                                    <td>{{$details->updated_at}}</td>
                                   <?php if($details['winner'] == 0){ 
                                        echo '<td>Not Yet Declared</td>'.$details['winner'];
                                    }else if($details['winner'] == 1){
                                        echo '<td>Winner</td>';
                                    }else if($details['inner'] == 2){ 
                                      echo '<td>Loser</td>';
                                   } ?>
                                </tr>
                                 <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                  </div>
                
                
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#myTable').DataTable();
});
</script>
@endsection
