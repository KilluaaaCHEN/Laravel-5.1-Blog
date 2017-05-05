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
    <li><a href="{{route('dict.index')}}">字典管理</a></li>
    @if($model->post_id)
        <li><a href="{{route('dict.show',['id'=>$model->id])}}">{{$model->name}}</a></li>
    @endif
    <li class="active">{{$title}}</li>
@endsection
@section('content')
    <br/>
    <div class="box box-success">
        <div class="box-body">
            <form action="{{route('dict.store')}}" method="POST" role="form" class="col-sm-7">
                @include('common.error')
                {!! csrf_field() !!}
                <input type="hidden" name="id" value="{{old('id',$model->id)}}"/>
                <div class="form-group">
                    <label class="control-label">键</label>
                    <input type="text" name="key" class="form-control" placeholder="请输入键" autocomplete="off" value="{{old('name',$model->key)}}">
                </div>
                <div class="form-group">
                    <label class="control-label">值</label>
                    <input type="text" name="val" class="form-control" placeholder="请输入值" autocomplete="off" value="{{old('url',$model->val)}}">
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