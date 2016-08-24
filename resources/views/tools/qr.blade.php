<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>二维码生成-larry666</title>
    <link href="{{asset('plugins/qr/styles.css')}}" rel="stylesheet">
    <script src="{{asset('plugins/common/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/qr/jquery-qrcode-0.14.0.min.js')}}"></script>
    <script src="{{asset('plugins/qr/scripts.js')}}"></script>
    <title>二维码生成-larry666</title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
<div id="container"></div>
<div style="width:600px; margin: 30px auto" class="form-group">
    <input id="text" value="{{$text}}" class="form-control" title="text"/>
</div>
</body>
</html>