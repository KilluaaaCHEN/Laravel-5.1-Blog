@extends('layouts.master')
@section('head')
    <script type="text/javascript" src="{{asset('/plugins/textSearch/jquery.textSearch-1.0.js')}}"></script>
    <script language="JavaScript">
        var sea = "{!! $q !!}";
        $(function () {
            if (sea) {
                $("#content").textSearch(sea, {markColor: "#EA4335"});
            }
        });
    </script>
@stop
@section('content')
    <title>Larry的博客 - Test</title>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">
                {!!$title!!}
            </h5>
        </div>
        <div class="panel-body" id="content">
            @foreach($posts as $item)
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="blog-title">
                            <a href="{{route('post.view',['post_id'=>$item->post_id])}}">{{$item->title}}</a>
                        </h2>
                        <ul class="list-inline index-desc">
                            <li><i class="icon-calendar"></i> {{$item->created_at->diffForHumans()}}</li>
                            <li><i class="icon-eye-open"></i> {{$item->read_count}} Browse</li>
                            <li><i class="icon-comment"></i>
                                <a href="{{route('post.view',['post_id'=>$item->post_id]).'#comments'}}">
                                    <span id = "sourceId::{{$item->post_id}}" class = "cy_cmt_count" >0</span>
                                    Comments</a>
                            </li>
                            <li class="tags">
                                <i class="icon-tags"></i>
                                @if($item->tags)
                                    <?php $tags = explode(",", rtrim($item->tags, ','))?>
                                    @foreach($tags as $i=>$tag)
                                        <a href="{{route('search.tag',['tag'=>$tag])}}">{{$tag}}</a>
                                        @if(count($tags)-1 > $i)
                                            ,
                                        @endif
                                    @endforeach
                                @endif
                            </li>
                        </ul>
                        <p>{{$item->desc}}</p>

                        <p class="text-right">
                            <a class="btn btn-success btn-sm" href="{{route('post.view',['post_id'=>$item->post_id])}}">
                                <i class="glyphicon glyphicon-new-window"></i> Read More
                            </a>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script id="cy_cmt_num" src="https://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cysUrqwHN">
    </script>
    {!! $posts->appends('q',$q)->render() !!}
    <div class="clearfix"></div>
@endsection
