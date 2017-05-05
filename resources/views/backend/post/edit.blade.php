@extends('backend.layouts.master')
@section('title')
    {{$title}}
    <small> 宝剑锋从磨砺出，梅花香自苦寒来。</small>
@endsection
@section('head')
    <link href="{{ asset('/plugins/tokenfield/css/bootstrap-tokenfield.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('/plugins/tokenfield/css/jquery-ui.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('backend/site.css') }}" type="text/css" rel="stylesheet">
    <title>{{$title}} - KilluaBlog</title>
@endsection
@section('breadcrumb')
    <li><a href="{{route('post.index')}}">文章管理</a></li>
    @if($model->post_id)
        <li><a href="{{route('post.show',['post_id'=>$model->post_id])}}">{{$model->title}}</a></li>
    @endif
    <li class="active">{{$title}}</li>
@endsection
@section('content')
    <br/>
    <div class="box box-success">
        <div class="box-body">
            <form action="{{route('post.store')}}" method="POST" role="form" class="col-sm-9">
                @include('common.error')
                {!! csrf_field() !!}
                <input type="hidden" name="post_id" value="{{old('post_id',$model->post_id)}}"/>
                <div class="form-group">
                    <label class="control-label">标题</label>
                    <input type="text" name="title" class="form-control" placeholder="请输入标题" autocomplete="off" value="{{old('title',$model->title)}}">
                </div>
                <div class="form-group">
                    <label class="control-label">标签</label>
                    <input type="text" name="tags" id="tags" class="form-control" placeholder="请输入标签" value="{{old('tags',$model->tags)}}">
                </div>
                <div class="form-group">
                    <label class="control-label">描述</label>
                    <input type="text" name="desc" class="form-control" placeholder="请输入描述" autocomplete="off" value="{{old('desc',$model->desc)}}">
                </div>
                <div class="form-group">
                    <label class="control-label">内容</label>
                    <div  id="markdown-editor">
                        <textarea name="content" class="form-control">{{old('content',$model->content)}}</textarea>
                       @include('common.markdown-editor')
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">状态</label>
                    <select name="state_id" class="form-control">
                        @foreach($model->getState() as $key=>$val)
                            <option value="{{$key}}" {{$key==old('state_id',$model->state_id)?'selected':''}}>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('footer')
    <script src="{{ asset('plugins/tokenfield/jquery-ui.js ') }}"></script>
    <script src="{{asset('plugins/tokenfield/bootstrap-tokenfield.js')}}"></script>
    <script type="text/javascript">
        var tf_source=eval('{!! \App\Models\Tag::getAllTag()!!}');
        $('[name=tags]').tokenfield({
            autocomplete: {
                source: tf_source,
                delay: 100
            },
            limit: 10,
            showAutocompleteOnFocus: true
        })
    </script>
@endsection