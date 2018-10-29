<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    {{--<link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">--}}
    <title>Admin Pro Admin Template - The Ultimate Bootstrap 4 Admin Template</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    {{--<link href="css/style.css" rel="stylesheet">--}}
    <!-- You can change the theme colors from here -->
    {{--<link href="css/colors/default-dark.css" id="theme" rel="stylesheet">--}}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="fix-header card-no-border fix-sidebar">
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">Admin Pro</p>
    </div>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar ">
        <nav class="navbar top-navbar navbar-expand-md navbar-light" >
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <div class="navbar-header">
                <a class="navbar-brand" href="index.html">
                    <!-- Logo icon --><b>
                        <img src="../resources/assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                    </b>
                    <!--End Logo icon -->
                    <!-- Logo text -->
                    <span>
                            <img src="../resources/assets/images/logo-text.png" alt="homepage" class="dark-logo" />
                        </span>
                </a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav mr-auto">
                    <!-- This is  -->
                    <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                </ul>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <ul class="navbar-nav my-lg-0">
                    <!-- ============================================================== -->
                    <!-- Search -->
                    <!-- ============================================================== -->
                    <li class="nav-item hidden-xs-down search-box"> <a class="nav-link hidden-sm-down waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                        <form class="app-search">
                            <input type="text" class="form-control" placeholder="Search & enter"> <a class="srh-btn"><i class="ti-close"></i></a>
                        </form>
                    </li>
                    <!-- ============================================================== -->
                    <!-- Profile -->
                    <!-- ============================================================== -->
                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-dark" href="#"><img src="../resources/assets/images/users/1.jpg" alt="user" class="profile-pic" /></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li>
                        <a class="waves-effect waves-dark fa fa-tachometer" href="#" aria-expanded="false">
                            <i class="mdi"></i>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark fa fa-user accordion"  href="#" aria-expanded="false">
                            <i class="mdi"></i>
                            <span class="hide-menu">users</span>
                        </a>
                        <div class="panel waves-effect waves-dark  ">
                            <a class="panel waves-effect waves-dark" href="{{route('user.create')}}">create users</a>
                            <a class="panel waves-effect waves-dark" href="{{route('user.index')}}">All accounts</a>
                        </div>

                    </li>

                    <li>
                        <a class="waves-effect waves-dark fa fa-bookmark accordion"  href="#" aria-expanded="false">
                            <i class="mdi"></i>
                            <span class="hide-menu">Type</span>
                        </a>
                        <div class="panel waves-effect waves-dark  ">
                            <a class="panel waves-effect waves-dark" href="{{route('type.create')}}">create type</a>
                            <a class="panel waves-effect waves-dark" href="{{route('type.index')}}">All types</a>
                        </div>

                    </li>
                    <li>
                        <a class="waves-effect waves-dark fa fa-bookmark accordion"  href="#" aria-expanded="false">
                            <i class="mdi"></i>
                            <span class="hide-menu">Posts</span>
                        </a>
                        <div class="panel waves-effect waves-dark  ">
                            <a class="panel waves-effect waves-dark" href="{{route('post.create')}}">create post</a>
                            <a class="panel waves-effect waves-dark" href="{{route('post.index')}}">All posts</a>
                        </div>

                    </li>
                    <li>
                        <a class="waves-effect waves-dark fa fa-bookmark accordion"  href="#" aria-expanded="false">
                            <i class="mdi"></i>
                            <span class="hide-menu">Posts</span>
                        </a>
                        <div class="panel waves-effect waves-dark  ">
                            <a class="panel waves-effect waves-dark" href="{{route('post.create')}}">create post</a>
                            <a class="panel waves-effect waves-dark" href="{{route('post.index')}}">All posts</a>
                        </div>

                    </li>




                </ul>
                <div class="text-center m-t-30">
                    <a href="https://wrappixel.com/templates/adminpro/" class="btn waves-effect waves-light btn-info hidden-md-down"> Upgrade to Pro</a>
                </div>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
    </aside>
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @yield('content')

                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
            @yield('footer')
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
{{--<script src="../assets/plugins/jquery/jquery.min.js"></script>--}}
{{--<!-- Bootstrap tether Core JavaScript -->--}}
{{--<script src="../assets/plugins/bootstrap/js/popper.min.js"></script>--}}
{{--<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>--}}
{{--<!-- slimscrollbar scrollbar JavaScript -->--}}
{{--<script src="js/perfect-scrollbar.jquery.min.js"></script>--}}
{{--<!--Wave Effects -->--}}
{{--<script src="js/waves.js"></script>--}}
{{--<!--Menu sidebar -->--}}
{{--<script src="js/sidebarmenu.js"></script>--}}
{{--<!--Custom JavaScript -->--}}
{{--<script src="js/custom.min.js"></script>--}}
<script src="{{asset('js/admin.js')}}"></script>
</body>

</html>