@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-9">
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
            </div>
        </div>
    </div>
</div>
@endsection
