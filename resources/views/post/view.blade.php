@extends('layouts.master')
@section('head')
    <link rel="stylesheet" href="{{asset('plugins/highlight/highlight.min.css')}}">
    <script src="{{asset('plugins/highlight/highlight.min.js')}}"></script>
    <script>hljs.initHighlightingOnLoad();</script>

    {{--<link href="{{asset('plugins/zoom/zoom.css')}}" rel="stylesheet">--}}
    {{--<script src="{{asset('plugins/zoom/zoom.min.js')}}"></script>--}}
    {{--<script src="{{asset('plugins/zoom/transition.js')}}"></script>--}}
@endsection
@section('content')
    <title>{{$post->title}}</title>
    <div id="blog">
        <h3 class="big-h3">{{$post->title}}</h3>
        <ul class="list-inline blog-desc">
            <li><i class="icon-calendar"></i> {{$post->created_at->diffForHumans()}}</li>
            <li><i class="icon-eye-open"></i> {{$post->read_count}} Browse</li>
            {{--<li><i class="icon-comment"></i>--}}
                {{--<a href="{{route('post.view',['post_id'=>$post->post_id]).'#disqus_thread'}}">{{$post->comment_count}} Comments</a>--}}
            {{--</li>--}}
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
    </div>
    <h3 id="comments"></h3>
    <h3 class="menu"><span>Comments</span></h3>
    <div id="disqus_thread"></div>
    {{--<script>--}}
        {{--var disqus_config = function () {--}}
            {{--this.page.identifier = '{{$post->post_id}}}';--}}
        {{--};--}}
        {{--var disqus_shortname = 'larry666';--}}
        {{--(function () {--}}
            {{--var d = document, s = d.createElement('script');--}}
            {{--s.src = '//' + disqus_shortname + '.disqus.com/embed.js';--}}
            {{--s.setAttribute('data-timestamp', +new Date());--}}
            {{--(d.head || d.body).appendChild(s);--}}
        {{--})();--}}
    {{--</script>--}}
    <!-- 多说评论框 start -->
    <div class="ds-thread" data-thread-key="{{$post->post_id}}" data-title="{{$post->title}}" data-url="{{route('post.view',['post_id'=>$post->post_id])}}"></div>
    <!-- 多说评论框 end -->
    <!-- 多说公共JS代码 start (一个网页只需插入一次) -->
    <script type="text/javascript">
        var duoshuoQuery = {short_name:"larry-cz"};
        (function() {
            var ds = document.createElement('script');
            ds.type = 'text/javascript';ds.async = true;
            ds.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//static.duoshuo.com/embed.js';
            ds.charset = 'UTF-8';
            (document.getElementsByTagName('head')[0]
            || document.getElementsByTagName('body')[0]).appendChild(ds);
        })();
    </script>
    <!-- 多说公共JS代码 end -->

@endsection

@section('footer')

@endsection