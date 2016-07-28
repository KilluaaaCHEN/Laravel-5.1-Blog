<!DOCTYPE html>
<!--
                                         __     __     __
   __                                   / /    / /    / /
  / /   __ _  _ __  _ __  __ _  _   _  / /_   / /_   / /_     ___  ___   _ __ ___
 / /   / _` || '__|| '__|/ _` || | | || '_ \ | '_ \ | '_ \   / __|/ _ \ | '_ ` _ \
/ /___| (_| || |   | |  | (_| || |_| || (_) || (_) || (_) |_| (__| (_) || | | | | |
\____/ \__,_||_|   |_|   \__,_| \__, | \___/  \___/  \___/(_)\___|\___/ |_| |_| |_|
                                |___/
--!>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Larry的博客,陈洲的博客,陈洲,Larry,博客,PHP博客,PHP,程序员的博客,Larry666,Larry.com"/>
    <meta name="description" content="Larry的博客专注于PHP技术研究及学习,以网站开发为核心的PHP技术交流博客,希望在今后写博客的道路上能结交很多志同道合的朋友!"/>
    <link href="{{asset('front/site.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/boot/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/boot/awesome.min.css')}}" rel="stylesheet">
    <script src="{{asset('plugins/common/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/boot/bootstrap.js')}}"></script>
    @yield('head')
</head>
<body>

<div class="wrap">
    <nav id="w0" class=" navbar-default navbar-fixed-top navbar" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w0-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span></button>
                <a class="navbar-brand" href="/"><img class="logo" src="{{asset('img/logo.png')}}" alt="Larry"></a>
            </div>
            <div id="w0-collapse" class="collapse navbar-collapse">
                <ul id="w1" class="navbar-nav navbar-right nav">
                    <li>
                        <a href="http://gjsq.me/10571644" target="_blank" style="padding:0px 50px 0px 0px">
                            <img src="https://www.getgreenjsq.org/aff/banners/01.gif" height="50"/>
                        </a>
                    </li>
                    <li class="active"><a href="/">Home</a></li>
                    <li><a href="{{route('post.view',['post_id'=>1])}}">About</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="site-index">
            <div class="body-content">
                <div class="row blog-page">
                    <div class="col-md-9 col-sm-12 md-margin-bottom-40" id="content">
                        @yield('content')
                    </div>
                    {{--右侧菜单--}}
                    <div class="col-md-3 col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"> <span class="icon-flag"></span> Read Ranking </h5>
                            </div>
                            <div class="panel-body">
                                <ul class="hot" id="read-ranking">
                                    @foreach(\App\Models\Post::getHotRead() as $key=>$item)
                                        <li>{{$key+1}}.<a href="{{route('post.view',['post_id'=>$item->post_id])}}">{{$item->title."($item->read_count)"}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <br/>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"> <span class="icon-tags"></span> Tags </h5>
                            </div>
                            <div class="panel-body tags-ranking">
                                @foreach(\App\Models\Post::getHotTag() as $item)
                                    <a href="{{route('search.tag',['tag'=>$item->tag_name])}}">{{$item->tag_name."($item->post_count)"}}</a>
                                @endforeach
                            </div>
                        </div>
                        <br/>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"> <span class="icon-link"></span> Friendly Link </h5>
                            </div>
                            <div class="panel-body link">
                                @foreach(\App\Models\Links::getLinks() as $item)
                                    <a href="{{$item->url}}" target="_blank">{{$item->name}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="footer">
    <p class="text-center">
        Copyright &copy; <?= date('Y') ?> Larry的博客<br/>
        渝ICP备15006619号-1<br/>
        <script type="text/javascript">
            var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
            document.write(unescape("%3Cspan id='cnzz_stat_icon_1255735945'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/z_stat.php%3Fid%3D1255735945%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));
        </script>
    </p>
</footer>
<a href="javascript:void(0)" class="go-top" title="Back to top">
    <img src="{{asset('img/top.png')}}" alt="Back to top">
</a>

</body>
{{--@include('common.pjax',['target'=>'#content'])--}}
</html>