<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>问卷调查</title>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            padding: 10px 35px;
        }

        .req_star:after {
            position: relative;
            top: 7px;
            left: 2px;
            color: #b10000;
            content: '*';
            font-size: 20px;
            line-height: 0;
        }

        .row {
            margin-top: 5px;
        }

        .radio label {
            margin-right: 20px
        }

        .checkbox label {
            margin-right: 20px
        }
    </style>
</head>
<body>
<form method="POST" action="{{url('/questionnaire/save')}}" accept-charset="UTF-8">
    {!! csrf_field() !!}
    <div class="container">
        <div class="row">
            <h3>请完成以下问卷调查</h3>
        </div>
        <div class="row">
            <label class="req_star">单选:</label>
            <div class="radio">
                <label>
                    <input required name="1" type="radio" value="非常满意">
                    非常满意
                </label>
                <label>
                    <input required name="1" type="radio" value="一般">
                    一般
                </label>
                <label>
                    <input required name="1" type="radio" value="不满意">
                    不满意
                </label>
            </div>
        </div>

        <div class="row">
            <label class="req_star">复选:</label>
            <div class="checkbox">
                <label>
                    <input name="2" type="checkbox" value="非常满意">
                    非常满意
                </label>
                <label>
                    <input name="2" type="checkbox" value="一般">
                    一般
                </label>
                <label>
                    <input name="2" type="checkbox" value="不满意">
                    不满意
                </label>
            </div>
        </div>

        <div class="row">
            <label class="req_star">数字:</label>
            <div class="">
                <input class="form-control " required name="3" type="number" value="" title="">
            </div>
        </div>

        <div class="row">
            <label class="req_star">单行文字:</label>
            <div class="">
                <input class="form-control " required name="4" type="text" value="" title="">
            </div>
        </div>

        <div class="row">
            <label class="req_star">多行文字:</label>
            <div class="">
                <textarea class="form-control " required name="5" rows="10" title=""></textarea>
            </div>
        </div>


        <div class="row">
            <input class="btn btn-info" type="submit" value="提交">
        </div>
    </div>
</form>
<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

