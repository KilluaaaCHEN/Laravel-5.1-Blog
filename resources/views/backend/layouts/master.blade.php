<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="{{asset('plugins/boot/bootstrap.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('backend/lte/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('backend/lte/css/ionicons.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('backend/lte/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('backend/lte/css/skins/skin-green.min.css')}}" rel="stylesheet" type="text/css"/>
    <script src="{{asset('backend/lte/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('backend/lte/js/respond.min.js')}}"></script>
    <script src="{{asset('plugins/common/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/boot/bootstrap.js')}}" type="text/javascript"></script>
    @yield('head')
</head>
<body class="skin-green sidebar-mini">
<div class="wrapper">
    <?php $user = Auth::user()?>
            <!-- Main Header -->
    <header class="main-header">
        <a href="{{route('admin.index')}}" class="logo">
            <span class="logo-mini"><b>Larry</b></span>
            <span class="logo-lg"><b>Larry</b>Blog</span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="javascript:void(0)" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown notifications-menu">
                        <a href="/" target="_blank">Frontend</a>
                    </li>
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">2</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 2 notifications</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> test....
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>

                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{$user->picture}}" class="user-image" alt="User Image"/>
                            <span class="hidden-xs">{{$user->name}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{$user->picture}}" class="img-circle" alt="User Image"/>

                                <p>
                                    Alexander Pierce - PHP Developer
                                    <small>{{date('M d, Y',strtotime($user->created_at))}}</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{route('admin.logout')}}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{$user->picture}}" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p>{{$user->name}}</p>
                    <a href="javascript:void(0)"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..."/>
                    <span class="input-group-btn">
                        <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i
                                    class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
            <ul class="sidebar-menu">
                <li class="header">内容管理</li>
                <li class="treeview" data-id="admin.post">
                    <a href="#"><i class='fa fa-book'></i> <span>博客管理</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('post.create')}}"><i class="fa fa-edit"></i>添加文章</a></li>
                        <li><a href="{{route('post.index')}}"><i class="fa fa-list-ul"></i>文章管理</a></li>
                    </ul>
                </li>
                <li class="treeview" data-id="admin.links">
                    <a href="#"><i class='fa fa-link'></i> <span>友链管理</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('links.create')}}"><i class="fa fa-edit"></i>添加友链</a></li>
                        <li><a href="{{route('links.index')}}"><i class="fa fa-list-ul"></i>友链管理</a></li>
                    </ul>
                </li>
                <li class="treeview" data-id="admin.dict">
                    <a href="#"><i class='fa fa-link'></i> <span>字典管理</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('dict.create')}}"><i class="fa fa-edit"></i>添加字典</a></li>
                        <li><a href="{{route('dict.index')}}"><i class="fa fa-list-ul"></i>字典管理</a></li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>

                @yield('title')
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('admin.index')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
                @yield('breadcrumb')
            </ol>
        </section>

        <section class="content">
            @if(Session::has('msg_state'))
                <div class="callout callout-{{Session::get('msg_state')?'success':'danger'}}">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>{{Session::get('msg_title')}}</h4>

                    <p>{{Session::get('msg_desc')}}</p>
                </div>
            @endif
            @yield('content')
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
        </div>
        <strong>Copyright &copy; 2015 <a href="#">Company</a>.</strong> All rights reserved.
    </footer>
    <aside class="control-sidebar control-sidebar-dark">
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class='control-sidebar-menu'>
                    <li>
                        <a href='javascript::;'>
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul><!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class='control-sidebar-menu'>
                    <li>
                        <a href='javascript::;'>
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul><!-- /.control-sidebar-menu -->

            </div><!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked/>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div><!-- /.form-group -->
                </form>
            </div><!-- /.tab-pane -->
        </div>
    </aside><!-- /.control-sidebar -->
    <div class='control-sidebar-bg'></div>
</div>
<script src="{{asset('backend/lte/js/app.min.js')}}" type="text/javascript"></script>
<script>
    $(function(){
        var current='{{\App\Helper\Path::getAlias()}}';
        console.log(current);
        $('.treeview[data-id="'+current+'"]').addClass('active');
    });
</script>
@yield('footer')
</body>
</html>