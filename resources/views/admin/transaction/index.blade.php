@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Transactions</div>
                    <div class="card-body">
                     <?php foreach($UserCompetition as $items){ ?>
                      
                     <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
