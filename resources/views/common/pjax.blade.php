
<link rel="stylesheet" href="https://cdn.bootcss.com/nprogress/0.2.0/nprogress.min.css">
<script type="text/javascript" src="https://cdn.bootcss.com/nprogress/0.2.0/nprogress.min.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/jquery.pjax/1.9.6/jquery.pjax.min.js"></script>
<script>
    $(document).ready(function()
    {
        var target='{{$target}}';
        $(document).pjax('a', target);

        $(document).on('pjax:start', function() {
            NProgress.start();
        });
        $(document).on('pjax:end', function() {
            NProgress.done();
            hljs.initHighlightingOnLoad();
        });
    });
</script>