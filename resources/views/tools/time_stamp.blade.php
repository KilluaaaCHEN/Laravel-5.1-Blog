<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>时间戳转换工具</title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>


<div style="width:300px; margin: 0 auto">
    <br/>
    <div class="form-group">
        <input type="text" class="form-control" id="time" placeholder="{{$d_time}}" value="{{$time}}">
    </div>
    <div class="row">
        <div class="col-sm-6">
            <button class="btn btn-default pull-right glyphicon glyphicon-arrow-down" onclick="toTs()"></button>
        </div>
        <div class="col-sm-6">
            <button class="btn btn-default glyphicon glyphicon-arrow-up" onclick="toTime()"></button>
        </div>
    </div>
    <br/>
    <div class="form-group">
        <input type="text" class="form-control" id="ts" placeholder="{{$d_ts}}" value="{{$ts}}">
    </div>
</div>

<script>
    function toTs() {
        var time = document.getElementById('time').value;
        if (time == '') {
            return false;
        }
        var dt = new Date(time);
        document.getElementById('ts').value = dt.getTime() / 1000;
    }

    function toTime() {
        var ts = document.getElementById('ts').value;
        if (ts == '') {
            return false;
        }
        if (/^(-)?\d{1,10}$/.test(ts)) {
            ts = ts * 1000;
        }
        var date = new Date(ts);
        var month = date.getMonth() + 1;
        if (month < 10) {
            month = '0' + month;
        }
        var day = date.getDate();
        if (day < 10) {
            day = '0' + day;
        }
        var hour = date.getHours();
        if (hour < 10) {
            hour = '0' + hour;
        }
        var minute = date.getMinutes();
        if (minute < 10) {
            minute = '0' + minute;
        }
        var second = date.getSeconds();
        if (second < 10) {
            second = '0' + second;
        }
        document.getElementById('time').value = date.getFullYear() + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
    }
</script>

</body>
</html>