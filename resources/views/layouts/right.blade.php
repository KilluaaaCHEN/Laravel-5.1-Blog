<div class="col-md-3 col-sm-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title"><span class="icon-flag"></span> Random Reading </h5>
        </div>
        <div class="panel-body">
            <ul class="hot" id="read-ranking">
                @foreach(\App\Models\Post::getRandomRead() as $key=>$item)
                    <li>{{$key+1}}.<a
                                href="{{route('post.view',['post_id'=>$item->post_id])}}">{{$item->title."($item->read_count)"}}</a>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
    <br/>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title"><span class="icon-tags"></span> Tags </h5>
        </div>
        <div class="panel-body tags-ranking  zzsc-container">
            <div id='tag-cloud'></div>
        </div>
        <script src="{{asset('plugins/tagcloud/jquery.svg3dtagcloud.min.js')}}"></script>
        <script>
            var tag_data = eval('(' + '{!! \App\Models\Post::getHotTag() !!}' + ')');
        </script>
        <script src="{{asset('plugins/tagcloud/tagcloud.js')}}"></script>
    </div>
    <br/>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title"><span class="icon-link"></span> Friendly Link </h5>
        </div>
        <div class="panel-body link">
            @foreach(\App\Models\Links::getLinks() as $item)
                <a href="{{$item->url}}" target="_blank">{{$item->name}}</a>
            @endforeach
        </div>
    </div>
</div>