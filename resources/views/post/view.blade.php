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
            <li><i class="icon-comment"></i>
                <a href="{{route('post.view',['post_id'=>$post->post_id]).'#disqus_thread'}}">Comments</a>
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
    </div>
    <h3 id="comments"></h3>
    <h3 class="menu"><span>Comments</span></h3>
    <div id="disqus_thread"></div>
    <script>
        (function() {
            var d = document, s = d.createElement('script');
            s.src = 'https://killua.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
    <script id="dsq-count-scr" src="//killua.disqus.com/count.js" async></script>
@endsection

@section('footer')

@endsection