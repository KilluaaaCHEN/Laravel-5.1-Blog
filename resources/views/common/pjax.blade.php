
<link rel="stylesheet" href="{{ asset('plugins/pjax/css/nprogress.css') }}">
<script type="text/javascript" src="{{ asset('plugins/pjax/js/jquery.pjax.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/pjax/js/nprogress.js') }}"></script>
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