@extends('layouts.master')
@section('head')
    <link rel="stylesheet" href="{{asset('plugins/highlight/highlight.min.css')}}">
    <script src="{{asset('plugins/highlight/highlight.min.js')}}"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <link href="{{asset('plugins/zoom/zoom.css')}}" rel="stylesheet">
    <script src="{{asset('plugins/zoom/zoom.min.js')}}"></script>
    <script src="{{asset('plugins/zoom/transition.js')}}"></script>
@endsection
@section('content')
    <title>{{$post->title}} | Killua Blog</title>
    <div class="col-md-9 col-sm-12 md-margin-bottom-40" id="content">
        <div id="blog">
            <h3 class="big-h3">{{$post->title}}</h3>
            <ul class="list-inline blog-desc">
                <li><i class="icon-calendar"></i> {{$post->created_at->diffForHumans()}}</li>
                <li><i class="icon-eye-open"></i> {{$post->read_count}} Browse</li>
                <li><i class="icon-comment"></i>
                    {{--                <a href="{{route('post.view',['post_id'=>$post->post_id]).'#disqus_thread'}}">Comments</a>--}}
                    <a href="#comments">
                        <span id="changyan_count_unit"></span> Comments
                    </a>
                </li>
                <li class="tags">
                    <i class="icon-tags"></i>
                    @if($post->tags)
                        <?php $tags = explode(",", rtrim($post->tags, ','))?>
                        @foreach($tags as $i=>$tag)
                            <a href="{{route('search.tag',['tag'=>$tag])}}">{{$tag}}</a>
                            @if(count($tags)-1 > $i)
                                ,
                            @endif
                        @endforeach
                    @endif
                </li>
            </ul>
            <div>
                {{$post->desc}}
            </div>
            <hr/>
        </div>
        <div class="clearfix"></div>
        <div>
            {!! \Plugins\MarkDownEditor\MdeDecode::decode($post->content) !!}
            @if($post->is_original==1)
            <blockquote>
                <p>本文为作者原创，允许转载，转载后请以<a href="{{URL::current()}}">链接形式</a>说明文章出处.
                    如转载但不标明来源，后果自负。</p>
            </blockquote>
            @endif
        </div>
        <h3 id="comments"></h3>
        <h3 class="menu"><span>Comments</span></h3>
        <script type="text/javascript" src="https://assets.changyan.sohu.com/upload/plugins/plugins.count.js">
        </script>
        <!--PC和WAP自适应版-->
        <div id="SOHUCS" sid="{{$post->post_id}}" ></div>
        <script type="text/javascript">
            (function(){
                var appid = 'cysUrqwHN';
                var conf = 'prod_c3b9787a045ea9d16b1c65cb5a05011a';
                var width = window.innerWidth || document.documentElement.clientWidth;
                if (width < 960) {
                    window.document.write('<script id="changyan_mobile_js" charset="utf-8" type="text/javascript" src="http://changyan.sohu.com/upload/mobile/wap-js/changyan_mobile.js?client_id=' + appid + '&conf=' + conf + '"><\/script>'); } else { var loadJs=function(d,a){var c=document.getElementsByTagName("head")[0]||document.head||document.documentElement;var b=document.createElement("script");b.setAttribute("type","text/javascript");b.setAttribute("charset","UTF-8");b.setAttribute("src",d);if(typeof a==="function"){if(window.attachEvent){b.onreadystatechange=function(){var e=b.readyState;if(e==="loaded"||e==="complete"){b.onreadystatechange=null;a()}}}else{b.onload=a}}c.appendChild(b)};loadJs("http://changyan.sohu.com/upload/changyan.js",function(){window.changyan.api.config({appid:appid,conf:conf})}); } })();
        </script>
    </div>
    @include('layouts.right')
@endsection

@section('footer')

@endsection