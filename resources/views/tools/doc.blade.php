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
            <label for="exampleInputEmail1">Title</label>
            <input type="text" class="form-control" name="title" value="{{ $title }}" title=""/>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">URI</label>
            <input type="text" class="form-control" name="uri" value="{{ $uri }}" title=""/>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Method</label>
            <div class="radio">
                <label>
                    <input type="radio" name="method" value="GET" {{$method=='GET'?'checked':''}}>
                    GET
                </label>
                &nbsp;
                <label>
                    <input type="radio" name="method" value="POST"
                            {{(!$method||$method=='POST')?'checked':''}}>
                    POST
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Request Parameter</label>
            <textarea class="form-control" rows=3 title="" name="request">{{ $req }}</textarea>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Response Format</label>
            <textarea class="form-control" rows=3 title="" name="response">{{ $res }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Generate</button>
    </form>
    @if($doc)
        <hr/>
        <input type="button" class="btn btn-primary" value="Copy To Clipboard" id="copy"/>
        <br/>
        <br/>
        <textarea id="doc" class="form-control" rows=5 onChange="clip.setText(this.value)"
                  title="">{!! $doc !!}</textarea>
        <script type="text/javascript" src="{{asset('/plugins/zeroClipboard/ZeroClipboard.js')}}"></script>
        <script language="JavaScript">
            var clip = null;
            init();
            function init() {
                clip = new ZeroClipboard.Client();
                clip.setHandCursor(true);

                clip.addEventListener('mouseOver', my_mouse_over);
                ZeroClipboard.setMoviePath("{{asset('/plugins/zeroClipboard/ZeroClipboard.swf')}}");
                clip.glue('copy');
            }

            function my_mouse_over(client) {
                clip.setText(document.getElementById('doc').value);
            }
        </script>
    @endif


</div>


</body>
</html>