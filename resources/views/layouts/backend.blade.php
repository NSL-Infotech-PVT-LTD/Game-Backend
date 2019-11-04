

<!DOCTYPE HTML>
<html>
    <head>
        <title>{{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="{{ config('app.name') }}" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <!-- Bootstrap Core CSS -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="{{ asset('template/css/style.css') }} " rel='stylesheet' type='text/css' />
        <!-- Graph CSS -->
        <link href="{{ asset('template/css/font-awesome.css') }}" rel="stylesheet">
        <!-- jQuery -->
        <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
        <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <!-- lined-icons -->
{{--        <link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />--}}
{{--        <script src="{{ asset('template/js/simpleCart.min.js') }} "> </script>--}}
{{--        <script src="{{ asset('template/js/amcharts.js') }} "></script>--}}
{{--        <script src="{{ asset('template/js/serial.js') }}  "></script>--}}
{{--        <script src="{{ asset('template/js/light.js') }} "></script>--}}
        <!-- //lined-icons -->
        <script src="{{ asset('template/js/jquery-1.10.2.min.js') }} "></script>
        <!--pie-chart--->
{{--        <script src="{{ asset('template/js/pie-chart.js') }} " type="text/javascript"></script>--}}
    </head>
    <body>
        <div class="page-container">
            <!--/content-inner-->
            <div class="left-content">
                <div class="inner-content">
                    <!-- header-starts -->
                    <div class="header-section">
                        <!-- top_bg -->
                        <div class="top_bg">

                            <div class="header_top">
                                <div class="top_right">
                                    <img style = "width:10%" src = "{{ asset('template/images/logo.png') }}">
                                </div>
                                <div class="top_left">


                                    <a class="#" style ="Color:White" href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                                <div class="clearfix"> </div>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                        <!-- /top_bg -->
                    </div>

                    <!-- //header-ends -->

                    <!--content-->
                    <div class="content">
                        <main class="py-4">
                            @if (Session::has('flash_message'))
                            <div class="container">
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    {{ Session::get('flash_message') }}
                                </div>
                            </div>
                            @endif

                            @yield('content')
                        </main>

                    </div>
                    <!--content-->
                </div>
            </div>
            <!--//content-inner-->
            <!--/sidebar-menu-->
            <div class="sidebar-menu">
                <header class="logo1">
                    <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a>
                </header>
                <div style="border-top:1px ridge rgba(255, 255, 255, 0.15)"></div>
                <div class="menu">
                    <ul id="menu" >
                        <li><a href="{{ url('admin/home')}}"><i class="fa fa-tachometer"></i> <span>Home</span></a></li>
                        <li><a href="{{ url('admin/users')}}"><i class="fa fa-users"></i> <span>Users</span></a></li>
                        <li><a href="{{ url('admin/roles')}}"><i class="fa fa-rocket"></i> <span>Roles</span></a></li>
                        <li><a href="{{ url('admin/permissions')}}"><i class="fa fa-rocket"></i> <span>Permissions</span></a></li>
                        <li><a href="{{ url('admin/product-category')}}"><i class="fa fa-rocket"></i> <span>Product Category</span></a></li>
                        <li class=""><a href="{{ url('admin/products')}}"><i class="fa fa-rocket"></i> <span>Products</span></a></li>

                        <li><a href="{{ url('admin/generator')}}"><i class="fa fa-bars"></i> <span>Generator</span></a></li>

                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <script>
            var toggle = true;

            $(".sidebar-icon").click(function() {
            if (toggle)
            {
            $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
            $("#menu span").css({"position":"absolute"});
            }
            else
            {
            $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
            setTimeout(function() {
            $("#menu span").css({"position":"relative"});
            }, 400);
            }

            toggle = !toggle;
            });
            $("#menu li a").each(function() {
                if ((window.location.href.indexOf($(this).attr('href'))) > -1) {
                    $(this).parent().addClass('active');
                }
            });
        </script>
        <!--js -->
{{--        <script src="{{ asset('template/js/jquery.nicescroll.js') }} "></script>--}}
{{--        <script src="{{ asset('template/js/scripts.js') }} "></script>--}}
{{--        <!-- Bootstrap Core JavaScript -->--}}
        <script src="{{ asset('template/js/bootstrap.min.js') }} "></script>
{{--        <!-- /Bootstrap Core JavaScript -->--}}
{{--        <!-- real-time -->--}}
{{--        <script language="javascript" type="text/javascript" src="js/jquery.flot.js"></script>--}}
{{--        <script src="{{ asset('template/js/jquery.fn.gantt.js') }} "></script>--}}
        <script src="{{ asset('template/js/menu_jquery.js') }}  "></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    </body>
</html>



