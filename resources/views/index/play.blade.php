<!DOCTYPE html>
<html>
<body>
    <video id="video1">
        <source src="{{$url}}" type="video/mp4">
        您的浏览器不支持 HTML5 video 标签。
    </video>
<script>
    document.getElementById("video1").play();
</script>
</body>
</html>
