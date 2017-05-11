<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Killua的博客,陈洲的博客,陈洲,Killua,博客,PHP博客,PHP,程序员的博客,KilluaChen的博客,KilluaChen.com"/>
    <meta name="description" content="Killua的博客专注于PHP技术研究及学习,以网站开发为核心的PHP技术交流博客,希望在今后写博客的道路上能结交很多志同道合的朋友!"/>
    <link href="{{asset('front/site.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link href="{{asset('plugins/boot/awesome.min.css')}}" rel="stylesheet">
    <script src="{{asset('plugins/common/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/boot/bootstrap.js')}}"></script>
    <script src="{{asset('front/site.js')}}"></script>
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
                <a class="navbar-brand" href="/"><img class="logo" src="{{asset('img/logo.png')}}" alt="Killua"></a>
            </div>
            <div id="w0-collapse" class="collapse navbar-collapse">
                <ul id="w1" class="navbar-nav navbar-right nav" style="margin-right: 10px;">
                    <li><a href="/">Home</a></li>
                    <li><a href="{{route('post.view',['post_id'=>1])}}">About</a></li>
                    <li role="presentation" class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            Tools <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('generate_doc')}}">Doc</a></li>
                            <li><a href="{{route('generate_code')}}">Code</a></li>
                            <li><a href="{{route('qr_code')}}">QrCode</a></li>
                            <li><a href="{{route('ts')}}">TimeStamp</a></li>
                        </ul>
                    </li>
                    <li><a href="https://github.com/KilluaChen" target="_blank">GitHub</a></li>
                    <li>
                        <form class="demo_search" action="/" method="get">
                            <i class="icon_search {{isset($q)?'hide':''}}" id="open"></i>
                            <input class="demo_sinput form-control {{isset($q)?'show':''}}" type="text" name="q"
                                   value="{{ isset($q)?$q:''}}"
                                   id="search_input" placeholder="Search...">
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="site-index">
            <div class="body-content">
                <div class="row blog-page">
                    @yield('content')
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="footer">
    <p class="text-center">
        Copyright &copy; <?= date('Y') ?> Killua Blog<br/>
        渝ICP备15006619号-1
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-99000479-1', 'auto');
            ga('send', 'pageview');
        </script>
    </p>
</footer>
<a href="javascript:void(0)" class="go-top" title="Back to top">
    <img src="{{asset('img/top.png')}}" alt="Back to top">
</a>
</body>
@include('common.pjax',['target'=>'#content'])
</html>