@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Edit {{ ucfirst(str_replace('_',' ',$meta->meta_key)) }}</div>
                <div class="card-body">
                    <a href="{{ url('/admin') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                    <br />
                    <br />

                    @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @endif

                    {!! Form::model($meta, [
                    'method' => 'PATCH',
                    'url' => ['/admin/metas', $meta->id],
                    'class' => 'form-horizontal',
                    'files' => true
                    ]) !!}

                    @include ('admin.metas.form', ['formMode' => 'edit'])

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
