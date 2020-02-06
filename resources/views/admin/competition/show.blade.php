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
<!--                    <a href="{{ url('/admin/competition/' . $competition->id . '/edit') }}" title="Edit Competition"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>-->
                    <!--                    {!! Form::open([
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
                                        {!! Form::close() !!}-->
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
                                            <img width="50" src="<?= url('noimage.png'); ?>">
                                        <?php endif; ?>    
                                    </td>
                                </tr>
                                <tr>
                                    <th> Description </th><td> {{ $competition->description }} </td></tr>
                                <tr>
                                    <th> Name </th><td> {{ $competition->name }} </td>
                                </tr>
                                <tr>
                                    <th> Start Time </th><td> {{ $competition->start_time }} </td>
                                </tr>
                                <tr>
                                    <th> Start Date </th><td> {{ $competition->date }} </td>
                                </tr>
                                <tr>
                                    <th> Fee </th><td> {{ $competition->fee }} </td>
                                </tr>
                                <tr>
                                    <th> Sequential Fee </th><td> {{ $competition->sequential_fee }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless data-table" >

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <?php
                                        foreach ($rules as $rule):
                                            if ($rule == 'user_id')
                                                $rule = 'User Name';
                                            ?>
                                            <th>{{ucfirst($rule)}}</th>
                                        <?php endforeach; ?>
                                        <!--<th>Action</th>-->
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
    var url = $(location).attr('href');
            var table = $('.data-table').DataTable({
    processing: true,
            serverSide: true,
            ajax: url,
            columns: [
            {data: 'id', name: 'id'},
<?php foreach ($rules as $rule): ?>
                {data: "{{$rule}}", name: "{{$rule}}"},
<?php endforeach; ?>
//            {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
    });
//deleting data
            $('.data-table').on('click', '.btnDelete[data-remove]', function (e) {
    e.preventDefault();
            var url = $(this).data('remove');
            swal.fire({
            title: "Are you sure want to remove this item?",
                    text: "Data will be Temporary Deleted!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Confirm",
                    cancelButtonText: "Cancel",
            }).then((result) => {
    Swal.showLoading();
            if (result.value) {
    $.ajax({
    url: url,
            type: 'DELETE',
            dataType: 'json',
            data: {method: '_DELETE', submit: true, _token: '{{csrf_token()}}'},
            success: function (data) {
            if (data == 'Success') {
            swal.fire("Deleted!", "Competition has been deleted", "success");
                    table.ajax.reload(null, false);
            }
            }
    });
    }
    });
    });
            $('.data-table').on('click', '.changeStatus', function (e) {
    e.preventDefault();
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            Swal.fire({
            title: 'Are you sure you wanted to change status?',
                    text: "You can revert this,in case you change your mind!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, ' + status + ' it!'
            }).then((result) => {
    Swal.showLoading();
            if (result.value) {
    var form_data = new FormData();
            form_data.append("id", id);
            form_data.append("status", status);
            form_data.append("_token", $('meta[name="csrf-token"]').attr('content'));
            $.ajax({
            url: "{{route('competition.confirmWinner')}}",
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
//                        Swal.showLoading();
                    },
                    success: function (data)
                    {
                    Swal.fire(
                            status + ' !',
                            'Winner has been ' + status + ' .',
                            'success'
                            ).then(() => {
                    table.ajax.reload(null, false);
                    });
                    }
            });
    }
    });
    });
    }
    );
</script>
@endsection
