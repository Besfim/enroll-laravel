<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>图片裁剪</title>
    <style>
        body {
            margin: 0;
            text-align: center;
        }
        #clipArea {
            margin: 20px;
            height: 300px;
        }
        #file,
        #clipBtn {
            margin: 20px;
        }
        #view {
            margin: 0 auto;
            width: 200px;
            height: 200px;
        }
    </style>
</head>
<body>
<div id="clipArea"></div>
<input type="file" id="file">
<button id="clipBtn">截取</button>
<div id="view"></div>

<script src="{{ asset('static/js/jquery-2.1.3.min.js') }}"></script>
<script src="{{ asset('static/js/hammer.js') }}"></script>
<script src="{{ asset('static/js/iscroll-zoom.js') }}"></script>
<script src="{{ asset('static/js/lrz.all.bundle.js') }}"></script>
<script src="{{ asset('static/js/jquery.photoClip.min.js') }}"></script>
<script>
    //document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    var clipArea = new bjj.PhotoClip("#clipArea", {
        size: [260, 260],
        outputSize: [640, 640],
        file: "#file",
        view: "#view",
        ok: "#clipBtn",
        loadStart: function() {
            console.log("照片读取中");
        },
        loadComplete: function() {
            console.log("照片读取完成");
        },
        clipFinish: function(dataURL) {
            console.log(dataURL);
        }
    });
    //clipArea.destroy();
</script>
</body>
</html>
