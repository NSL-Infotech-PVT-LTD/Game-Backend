@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">User</div>
                <div class="card-body">

                    <a href="{{ url('/admin/users') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
<!--                        <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" title="Edit User"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>-->
                    <!--                        {!! Form::open([
                                                'method' => 'DELETE',
                                                'url' => ['/admin/users', $user->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'title' => 'Delete User',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                ))!!}
                                            {!! Form::close() !!}-->
                    <br/>
                    <br/>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <?php foreach (['id', 'full_name', 'email', 'mobile', 'country', 'image'] as $attribute):
                                    ?>
                                    <tr>
                                        <th>{{ucfirst($attribute)}}</th>
                                        <?php
                                        if ($attribute == 'image'):
                                            if (empty($user->image)) {
                                                echo "<td><img width='150' src=" . url('uploads/competition/noimage.png') . "></td>";
                                            } else {
                                                echo "<td><img width='150' src=" . url('uploads/users/' . $user->image) . "></td>";
                                            }
                                            ?>
                                        <?php else: ?>
                                            <td>{{ $user->$attribute }}</td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
