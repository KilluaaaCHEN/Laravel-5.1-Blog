<script src="{{asset('plugins/common/jquery.min.js')}}"></script>




<link rel="stylesheet" href="{{asset('plugins/mde/css/editormd.min.css')}}" />
<script src="{{asset('plugins/mde/editormd.min.js')}}"></script>

<meta name="csrf-token" content="{{ csrf_token() }}" />
<script type="text/javascript">
    var mde;
    $(function() {
        mde = editormd("markdown-editor", {
            height:500,
            placeholder:'请输入内容...',
            syncScrolling : "single",
            path    : "/plugins/mde/lib/",
            toolbarAutoFixed:false,//关闭固定定位
            emoji : true,//开启表情
            dialogMaskOpacity : 0.3,//遮罩层的透明度
            imageUpload : true,
            imageFormats : ["jpg", "jpeg", "gif", "png"],
            imageUploadURL : "{{route('admin.upload')}}",
            codeFold : true,
            toolbarIcons:function() {
                return ["bold", "del", "italic", "quote", "ucwords", "uppercase", "lowercase",
                    "|", "list-ul", "list-ol", "hr", "|", "link", "image", "code", "code-block", "table", "emoji",
                    "|", "watch", "preview", "fullscreen", "clear", "|", "help", "info"];
            }
        });
    });
</script>
