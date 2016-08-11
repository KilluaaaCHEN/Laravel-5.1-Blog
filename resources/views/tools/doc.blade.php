<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API文档生成工具</title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>


<div style="width:500px; margin: 30px auto">
    <br/>
    <form method="POST">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="exampleInputEmail1">URI</label>
            <input type="text" class="form-control" name="uri" placeholder="/module/controller/action"
                   value="{{ old('uri') }}">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Method</label>
            <div class="radio">
                <label>
                    <input type="radio" name="method" value="GET" {{old('method')=='GET'?'checked':''}}>
                    GET
                </label>
                &nbsp;
                <label>
                    <input type="radio" name="method" value="POST"
                            {{(!old('method')||old('method')=='POST')?'checked':''}}>
                    POST
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Request Parameter</label>
            <textarea class="form-control" title="" name="request">{{ old('request') }}</textarea>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Response Format</label>
            <textarea class="form-control" title="" name="response">{{ old('response') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Generate</button>
    </form>
    <hr/>
    <p>
        {!! Session::get('doc') !!}
    </p>
</div>

</body>
</html>