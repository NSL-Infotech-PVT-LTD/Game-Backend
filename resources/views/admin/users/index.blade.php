@extends('layouts.backend')
<style>
    table.dataTable thead th.select-checkbox:before {
        content: ' ';
        margin-top: -6px;
        margin-left: -6px;
        border: 1px solid black;
        border-radius: 3px;
    }

    table.dataTable thead th.select-checkbox:before, table.dataTable thead th.select-checkbox:after {
        display: block;
        position: absolute;
        top: 13em;
        left: 10%;
        width: 12px;
        height: 12px;
        box-sizing: border-box;
    }
    table.dataTable tr th.select-checkbox.selected::after {
        content: "✔";
        margin-top: -11px;
        margin-left: -4px;
        text-align: center;
        text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }


</style>
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" media="screen" />
<script charset="utf8" src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>



<!--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>


<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.js"></script>


<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/tabletools/2.2.4/js/dataTables.tableTools.min.js"></script>
<!--/Data Table Declarations-->
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><b>Users</b></div>
                <div class="card-body">
                    <!--                    <a href="{{ url('/admin/users/create') }}" class="btn btn-success btn-sm" title="Add New User">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                                        </a>-->
                    <a class="btn btn-info btn-sm" title="Notify" id="checkBoxSelected">
                        <i class="fa fa-bell" aria-hidden="true"></i> Notify</a>
                    <div class ="table-responsive">
                        <table class="table table-borderless data-table" >

                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <?php foreach ($rules as $rule): ?>
                                        <th>{{ucfirst($rule)}}</th>
                                    <?php endforeach; ?>
                                    <th>Action</th>
                                    <!--<th>Transaction</th>-->
                                </tr>
                            </thead>
                        </table>                       
                        <div id="example" class="small-6 columns"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
//    $('.updateStatus').on('click',function(event){
//// 	event.preventDefault();
//        var th =$(this);
//        th.prop('checked',false);
//        var id = $(this).attr('data-id');
//        $.ajax({
//            url: "updateUserStatus?id="+id,
//            type: 'GET',
//            success: function(res) {
//                // alert(res.status);
//                if(res.status==false){
//                    // $(this).attr("checked");
//                    th.prop('checked',false);
//                    Swal.fire(
//                        'Notify',
//                        res.message,
//                        'error'
//                    );
//                }else{
//                    th.prop('checked',true);
//                }
//            }
//        });
//    });


$(function () {
var table = $('.data-table').DataTable({
        "dom": 'lBfrtip',
        "buttons": ['print', 'excel', 'pageLength'],
        columnDefs: [ {
        orderable: false,
                className: 'select-checkbox',
                targets: 0,
                data: null,
                defaultContent: ''
        } ],
        select: {
        style:    'multi',
                selector: 'td:first-child'
        },
        order: [[ 1, 'asc' ]],
//        "sDom": 'lBfrtip',
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.index') }}",
        columns: [
        {data: '', name: ''},
        {data: 'id', name: 'id'},
<?php foreach ($rules as $rule): ?>
            {data: "{{$rule}}", name: "{{$rule}}"},
<?php endforeach; ?>
        {data: 'action', name: 'action', orderable: false, searchable: false},
//                    {data: 'transaction', name: 'transaction', orderable: false, searchable: false},
        ]
        });
        table.buttons().container().appendTo( $('#example') );
        table.on("click", "th.select-checkbox", function() {
        if ($("th.select-checkbox").hasClass("selected")) {
        table.rows().deselect();
                $("th.select-checkbox").removeClass("selected");
        } else {
        table.rows().select();
                $("th.select-checkbox").addClass("selected");
        }
        }).on("select deselect", function() {
("Some selection or deselection going on")
        if (table.rows({
        selected: true
        }).count() !== table.rows().count()) {
$("th.select-checkbox").removeClass("selected");
        } else {
$("th.select-checkbox").addClass("selected");
        }
});
        $('#checkBoxSelected').on('click', function (e) {
var data = table.rows({ selected: true }).data().pluck('id').toArray();
        if (data.length < 1){
Swal.fire('info', 'You have to select one row first', 'alert').then(() => {
table.ajax.reload(null, false);
        });
        return true;
}
Swal.fire({
html: '<input class="form-control" placeholder="Title" type="text" name="title"><textarea name="" class="form-control description" placeholder="Add Description"></textarea>',
        title: 'Add Details to Notify Competition Players',
        text: "You can revert this,in case you change your mind!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, ' + status + ' it!'
        }).then((result) => {
if ($('.description').val().length > 0 && $('input[name="title"]').val().length > 0){
Swal.showLoading();
        if (result.value) {
var form_data = new FormData();
        form_data.append("description", $('.description').val());
        form_data.append("id", data);
        form_data.append("title", $('input[name="title"]').val());
        form_data.append("_token", $('meta[name="csrf-token"]').attr('content'));
        $.ajax({
        url: "{{route('users.notify')}}",
                method: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {

                setTimeout(function () {
                Swal.showLoading();
                }, 1000);
                },
                success: function (data)
                {
                Swal.fire(
                        status + 'Sent',
                        'Notification has been sent .',
                        'Sent'
                        ).then(() => {
                table.ajax.reload(null, false);
                });
                }
        });
        }
}
});
        console.log(data);
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
        if (data.success) {
        swal.fire("Deleted!", data.message, "success");
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
        url: "{{route('users.changeStatus')}}",
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
                        'User has been ' + status + ' .',
                        'success'
                        ).then(() => {
                table.ajax.reload(null, false);
                });
                }
        });
        }
});
        });
        });

</script>
@endsection
