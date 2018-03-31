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
                {{--<li><i class="icon-calendar"></i> {{$post->created_at->diffForHumans()}}</li>--}}
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
                    <p>本文为作者原创，允许转载，转载后请以<a href="{{URL::current()}}">链接形式</a>说明文章出处. 如转载但不标明来源，后果自负。</p>
                </blockquote>
            @endif
        </div>


        <h3 class="menu ">
            <span>Comments</span>
            <font class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top"
               title="无法加载评论?翻个墙试试!"></font></h3>

        <div id="disqus_thread"></div>
        <script>
            (function () { // DON'T EDIT BELOW THIS LINE
                var d = document, s = d.createElement('script');
                s.src = 'https://killuachen.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
            })();
        </script>
    </div>
    @include('layouts.right')
@endsection

@section('footer')

@endsection