<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css' />
<?php
//dd($item);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 " style="margin-left:25% ">
            <div class="tile">
                <div class="wrapper">
                    <div class="banner-img">
                        <img src="{{ url('uploads/events/'.$item->image)}}" alt="Image 1">
                    </div>
                    <div class="header" style="font-size: 30px">{{$item->name}}</div>


                    <div class="dates">
                        <div class="date float-left">
                            <strong>{{$item->date}}</strong>
                            <span></span>
                        </div>
                        <div class="time float-right">
                            {{$item->from_time}}<strong> - </strong>{{$item->to_time}}
                                <span></span>
                        </div>
                    </div>


                    <div class="stats row container">
                        {{$item->description}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
