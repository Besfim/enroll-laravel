<div id="loading" style="position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: white;text-align: center;font-size: 22px;font-family: 幼圆;z-index: 100000;"><p style="margin-top: 300px;"></p></div>
<script>
    $(document).ready(function() {
        var loading = document.getElementById('loading');

        $("#loading").animate({"opacity":"0"},1000);
        setTimeout(function(){loading.style.display = 'none'},1000);
    });
</script>