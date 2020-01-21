@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Transactions</div>
                <div class="card-body">
                    
                    <div class ="table-responsive">
                        <table class="table table-borderless data-table" >
                        
                                <thead>
                                <tr>
                            <th>id</th>
                            <th>Amount</th>
                            <th>Balance Transaction</th>
                        </tr>
                        <?php foreach ($UserCompetition as $items) { ?>
                        
                        <tr>
                            <th><?php echo $items['id']; ?></th>
                            <th><?php $json = json_decode($items['payment_param_1']); $amt = $json->amount ; echo '$'.$amt/100;?></th>
                            <th><?php $json = json_decode($items['payment_param_1']); echo '$'.$json->balance_transaction ; ?></th>
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
@endsection
