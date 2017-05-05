@extends('backend.layouts.master')
@section('title')
    {{$title}}
    <small> 宝剑锋从磨砺出，梅花香自苦寒来。</small>
@endsection
@section('head')
    <link href="{{ asset('backend/site.css') }}" type="text/css" rel="stylesheet">
    <title>{{$title}} - KilluaBlog</title>
@endsection
@section('breadcrumb')
    <li><a href="{{route('links.index')}}">友链管理</a></li>
    @if($model->post_id)
        <li><a href="{{route('links.show',['id'=>$model->id])}}">{{$model->name}}</a></li>
    @endif
    <li class="active">{{$title}}</li>
@endsection
@section('content')
    <br/>
    <div class="box box-success">
        <div class="box-body">
            <form action="{{route('links.store')}}" method="POST" role="form" class="col-sm-7">
                @include('common.error')
                {!! csrf_field() !!}
                <input type="hidden" name="id" value="{{old('id',$model->id)}}"/>
                <div class="form-group">
                    <label class="control-label">名称</label>
                    <input type="text" name="name" class="form-control" placeholder="请输入名称" autocomplete="off" value="{{old('name',$model->name)}}">
                </div>
                <div class="form-group">
                    <label class="control-label">地址</label>
                    <input type="text" name="url" class="form-control" placeholder="请输入地址" autocomplete="off" value="{{old('url',$model->url)}}">
                </div>
                <div class="form-group">
                    <label class="control-label">排序</label>
                    <input type="text" name="sort_num" class="form-control" placeholder="请输入排序编号" autocomplete="off" value="{{old('sort_num',$model->sort_num)}}">
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer')
@endsection