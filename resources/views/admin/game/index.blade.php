@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Game</div>
                <div class="card-body">
                    <a href="{{ url('/admin/game/create') }}" class="btn btn-success btn-sm" title="Add New Game">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                    </a>

                    {!! Form::open(['method' => 'GET', 'url' => '/admin/game', 'class' => 'form-inline my-2 my-lg-0 float-right', 'role' => 'search'])  !!}
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                        <span class="input-group-append">
                            <button class="btn btn-secondary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                    {!! Form::close() !!}

                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>#</th><th>Name</th><th>Image</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($game as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>

                                        <?php if (file_exists(public_path(App\Http\Controllers\Admin\GameController::$_mediaBasePath . $item->image))): ?>
                                            <img width="50" src="<?= url(App\Http\Controllers\Admin\GameController::$_mediaBasePath . $item->image) ?>">
                                        <?php else: ?>
                                            <!--<span>-</span>-->
            <img width="50" src="<?= url(App\Http\Controllers\Admin\GameController::$_mediaBasePath . 'noimage.png') ?>">
                                        <?php endif; ?>                        



                                    </td>
                                    <td>
                                        <a href="{{ url('/admin/game/' . $item->id) }}" title="View Game"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                        <a href="{{ url('/admin/game/' . $item->id . '/edit') }}" title="Edit Game"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'url' => ['/admin/game', $item->id],
                                        'style' => 'display:inline'
                                        ]) !!}
                                        {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-sm',
                                        'title' => 'Delete Game',
                                        'onclick'=>'return confirm("Confirm delete?")'
                                        )) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination-wrapper"> {!! $game->appends(['search' => Request::get('search')])->render() !!} </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
