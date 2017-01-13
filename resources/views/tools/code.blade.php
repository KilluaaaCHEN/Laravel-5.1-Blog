<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ICC-Gii生成工具-larry666</title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="{{asset('plugins/common/jquery.min.js')}}"></script>

</head>
<body>


<div style="width:500px; margin: 30px auto">
    <br/>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST">
        {!! csrf_field() !!}
        <div class="form-group">
            <label>DB</label>
            <input type="text" class="form-control" name="db" value="{{ old('db','default') }}" title=""
                   placeholder="default"/>
        </div>
        <div class="form-group">
            <label>Collection</label>
            <input type="text" class="form-control" name="coll" value="{{ old('coll') }}" title="" id="coll"/>
        </div>
        <div class="form-group">
            <label>Module</label>
            <input type="text" class="form-control" name="module" value="{{ old('module') }}" title="" id="module"/>
        </div>
        <div class="form-group">
            <label>Controller</label>
            <input type="text" class="form-control" name="ctrl" value="{{ old('ctrl')}}" title="" id="ctrl"/>
        </div>
        <div class="form-group">
            <label>Model</label>
            <input type="text" class="form-control" name="model" value="{{ old('model')}}" title="" id="model"/>
        </div>
        <div class="form-group">
            <label>Delete</label>
            <input type="text" class="form-control" name="delete" value="{{ old('delete')}}" id="model"/>
        </div>
        <div class="form-group">
            <label>Structure</label>
            <textarea class="form-control" rows=3 title="" name="structure">{{ old('structure') }}</textarea>
        </div>
        <div class="form-group">
            <label>Type</label>
            <div class="radio">
                <label>
                    <input type="radio" name="type" value="model" {{(!old('type')||old('type')=='model')?'checked':''}}>
                    Model
                </label>
                <label>
                    <input type="radio" name="type" value="controller" {{(old('type')=='controller')?'checked':''}}>
                    Controller
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Generate</button>
    </form>

    <script>
        $(function () {
            $('#coll').change(function () {
                var val = $(this).val().split("_");
                var ctrl_name = '';
                for (var i = 0; i < val.length; i++) {
                    ctrl_name += szmdx(val[i]);
                }
                $('#ctrl').val(ctrl_name + 'Controller');
                $('#model').val(ctrl_name);
            });
            $('#module').change(function () {
                $(this).val(szmdx($(this).val()));
            });
        });

        function szmdx(str) {
            var reg = /\b(\w)+\b/g;
            return str.replace(reg, function (word) {
                return word.replace(word.charAt(0), word.charAt(0).toUpperCase());
            });
        }
    </script>
    {{--@if($doc)--}}
    {{--<hr/>--}}
    {{--<input type="button" class="btn btn-primary" value="Copy To Clipboard" id="copy"/>--}}
    {{--<br/>--}}
    {{--<br/>--}}
    {{--<textarea id="doc" class="form-control" rows=5 onChange="clip.setText(this.value)" title="">{!! $doc !!}</textarea>--}}
    {{--<script type="text/javascript" src="{{asset('/plugins/zeroClipboard/ZeroClipboard.js')}}"></script>--}}
    {{--<script language="JavaScript">--}}
    {{--var clip = new ZeroClipboard.Client();--}}
    {{--clip.setText(document.getElementById('doc').value);--}}
    {{--ZeroClipboard.setMoviePath("{{asset('/plugins/zeroClipboard/ZeroClipboard.swf')}}");--}}
    {{--clip.glue('copy');--}}
    {{--clip.addEventListener('mouseup', function (client) {--}}
    {{--document.getElementById('copy').value = 'Copy Success';--}}
    {{--});--}}
    {{--</script>--}}
    {{--@endif--}}
</div>
@include('/common/stat')
</body>
</html>