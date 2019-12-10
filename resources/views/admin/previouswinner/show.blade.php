@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Previouswinner {{ $previouswinner->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/admin/previouswinner') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/previouswinner/' . $previouswinner->id . '/edit') }}" title="Edit Previouswinner"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['admin/previouswinner', $previouswinner->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete Previouswinner',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $previouswinner->id }}</td>
                                    </tr>
                                    <tr><th> Title </th><td> {{ $previouswinner->title }} </td></tr><tr><th> Description </th><td> {{ $previouswinner->description }} </td></tr><tr><th> Image </th>
                                        <td> 
                                         <?php if (file_exists(public_path(App\Http\Controllers\Admin\PreviouswinnerController::$_mediaBasePath . $previouswinner->image)) && !empty($previouswinner->image)): ?>
        <img width="50" src="<?= url(App\Http\Controllers\Admin\PreviouswinnerController::$_mediaBasePath . $previouswinner->image) ?>">
                                        <?php else: ?>
                                            <!--<span>-</span>-->
        <img width="50" src="<?= url('noimage.png') ?>">
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
