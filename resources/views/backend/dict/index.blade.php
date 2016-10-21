@extends('backend.layouts.master')
@section('title')
    字典列表
    <small> 宝剑锋从磨砺出，梅花香自苦寒来。</small>
@endsection
@section('breadcrumb')
    <li><a href="{{route('admin.dict.index')}}">字典管理</a></li>
    <li class="active">字典列表</li>
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
                            <th class="sorting" width="200">key</th>
                            <th class="sorting" width="200">val</th>
                            <th width="200">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{$item->key}}</td>
                                <td>{{$item->val}}</td>
                                <td class="operation">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                            管理 <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{route('admin.dict.edit',$item->id)}}">编辑</a></li>
                                            <li><a href="{{route('admin.dict.delete',$item->id)}}" onclick="return confirm('是否删除?')">删除</a></li>
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
                    @unless($items->isEmpty())
                        <?php $items_arr = $items->toArray()?>
                        显示 {{$items_arr['from']}} - {{$items_arr['to']}} ,共 {{$items->total()}} 条数据。
                    @endunless
                </div>
                <div class="col-sm-7 dataTables_paginate">
                    {!! $items->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
@endsection