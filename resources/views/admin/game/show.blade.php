@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Game {{ $game->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/admin/game') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/game/' . $game->id . '/edit') }}" title="Edit Game"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['admin/game', $game->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete Game',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $game->id }}</td>
                                    </tr>
                                    <tr><th> Name </th><td> {{ $game->name }} </td></tr><tr>
                                        <th> Image </th>
                                        <td> 
        <?php if (file_exists(public_path(App\Http\Controllers\Admin\GameController::$_mediaBasePath . $game->image))): ?>
        <img width="50" src="<?= url(App\Http\Controllers\Admin\GameController::$_mediaBasePath . $game->image) ?>">
        <?php else: ?>
        <img width="50" src="<?= url(App\Http\Controllers\Admin\GameController::$_mediaBasePath . 'noimage.png') ?>">
                <!--<span>-</span>-->
        <?php endif; ?>                            
        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
