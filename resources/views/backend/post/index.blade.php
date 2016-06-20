@extends('backend.layouts.master')
@section('title')
    文章列表
    <small> 宝剑锋从磨砺出，梅花香自苦寒来。</small>
@endsection
@section('breadcrumb')
    <li><a href="{{route('admin.post.index')}}">文章管理</a></li>
    <li class="active">文章列表</li>
@endsection
@section('head')
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('backend/site.css')}}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="box box-success">
        <div class="box-header">
            {{--<form>--}}
                {{--<input type="text" name="test"/>--}}
                {{--<input type="submit" value="Submit"/>--}}
            {{--</form>--}}
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered table-hover dataTable table-striped">
                        <thead>
                        <tr role="row">
                            <th class="sorting_asc" sort_type="desc" sort_field="title" width="200">标题</th>
                            <th class="sorting_desc" width="200">描述</th>
                            <th class="sorting" width="80">状态</th>
                            <th class="sorting" width="80">阅读数</th>
                            <th class="sorting" width="80">评论数</th>
                            <th class="sorting" width="150">更新时间</th>
                            <th width="100">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td><a href="{{route('admin.post.show',$post->post_id)}}">{{$post->title}}</a>
                                </td>
                                <td>{{\Illuminate\Support\Str::limit($post->desc,30)}}</td>
                                <td>{{$post->getState($post->state_id)}}</td>
                                <td>{{$post->read_count}}</td>
                                <td>{{$post->comment_count}}</td>
                                <td>{{$post->updated_at}}</td>
                                <td class="operation">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                            管理 <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{route('admin.post.edit',$post->post_id)}}">编辑</a></li>
                                            <li><a href="{{route('admin.post.delete',$post->post_id)}}" onclick="return confirm('是否删除?')">软删除</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>没有找到数据</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5 dataTables_info">
                    @unless($posts->isEmpty())
                        <?php $posts_arr = $posts->toArray()?>
                        显示 {{$posts_arr['from']}} - {{$posts_arr['to']}} ,共 {{$posts->total()}} 条数据。
                    @endunless
                </div>
                <div class="col-sm-7 dataTables_paginate">
                    {!! $posts->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
@endsection