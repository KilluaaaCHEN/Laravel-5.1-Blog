<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>KilluaBlog-Admin | Log in</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="{{asset('/plugins/boot/bootstrap.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/lte/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/lte/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/lte/css/skins/skin-green.min.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('backend/lte/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('backend/lte/js/respond.min.js')}}"></script>
    <link href="{{asset('plugins/iCheck/square/green.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .form-control-feedback{top:0 !important;}
    </style>
</head>
<body class="login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0)"><b>Killua</b>-Blog</a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        @include('common.error')
        <form method="post">
            {!! csrf_field() !!}
            <div class="form-group has-feedback">
                <input type="text" name="email" class="form-control" placeholder="Email" value="{{old('email')}}"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Password" value="{{old('password')}}"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" {{old('remember')?'checked':''}}> Remember Me
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-success btn-block btn-flat">Sign In</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{{asset('/plugins/common/jquery.min.js')}}"></script>
<script src="{{asset('/plugins/boot/bootstrap.js')}}" type="text/javascript"></script>
<script src="{{asset('plugins/iCheck/icheck.min.js')}}" type="text/javascript"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
            increaseArea: '20%'
        });
    });
</script>
</body>
</html>