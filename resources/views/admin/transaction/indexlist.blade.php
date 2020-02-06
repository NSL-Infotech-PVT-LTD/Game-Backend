@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Transactions</div>
                <div class="card-body">

                    <div class ="table-responsive">
                        <table class="table table-borderless data-table" >

                            <thead style="text-align: center">
                                <tr>
                                    <th>id</th>
                                    <!--<th>score</th>-->
                                    <th>Player</th>
                                    <!--<th>status</th>-->
                                    <th>Competition id</th>
                                    <th>Payment</th>
                                </tr>
                                <?php
                                foreach ($userCompetition as $items) {
//                                    dd($items->toArray());
                                    ?>
                                    <tr>
                                        <td><?= $items->id ?></td>
                                        <!--<td><?= $items->score ?></td>-->
                                        <td><a href="{{url('admin/users/'.$items->player_id)}}"><?= App\User::whereId($items->player_id)->first()->fullname ?></a></td>
                                        <!--<td><?= $items->status ?></td>-->
                                        <td><a href="{{url('admin/competition/'.$items->competition_id)}}"><?= App\Competition::whereId($items->competition_id)->first()->name ?></a></td>
                                        <td>
                                            <div class="text-center">
                                                <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalL{{$items->id}}"><?= 'Payment Details (' . count($items->payments) . ')' ?></a>
                                            </div>
                                            <div class="modal fade" id="modalL{{$items->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                                 aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header text-center">
                                                            <h4 class="modal-title w-100 font-weight-bold">Payments</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body mx-3">

                                                            <table>
                                                                <tr>
                                                                    <th>Amount</th>
                                                                    <th>Date</th>
                                                                    <th>Transaction Id</th>
                                                                </tr> 
                                                                <?php
                                                                foreach ($items->payments as $paymentobj):
                                                                    $payment = json_decode($paymentobj->payment);
                                                                    ?>
                                                                    <tr> 
                                                                        <td><b>USD {{$payment->amount/100}}</b></td>
                                                                        <?php if (isset($payment->created)): ?>
                                                                            <td>{{date('Y M, d | h:i:s A',$payment->created)}}</td>
                                                                        <?php else: ?>
                                                                            <td>{{$paymentobj->created_at}}</td>
                                                                        <?php endif; ?>
                                                                        <td>{{$payment->id}}</td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                <?php } ?>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .modal-dialog {
        max-width: 800px !important;
    }
</style>
@endsection
