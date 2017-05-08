@extends('layouts.master')
@section('head')
    <title>二维码生成工具 | Killua Chen</title>
    <link href="{{asset('plugins/qr/styles.css')}}" rel="stylesheet">
    <script src="{{asset('plugins/common/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/qr/jquery-qrcode-0.14.0.min.js')}}"></script>
    <script src="{{asset('plugins/qr/scripts.js')}}"></script>
@endsection
@section('content')
    <div id="container"></div>
    <div style="width:600px; margin: 30px auto" class="form-group">
        <input id="text" value="{{$text?:URL::current()}}" class="form-control" title="text"/>
    </div>
@endsection
