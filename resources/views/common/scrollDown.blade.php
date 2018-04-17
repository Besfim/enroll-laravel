<p id="scrollDown" style="opacity: 0.5;color: gray;text-align: center;position: fixed;top: 540px;width: 100%;font-size: 26px;opacity: 0">︾</p>
<script>
    $("#scrollDown").delay(2000).animate({"opacity":"1"},1000).animate({"opacity":"0.3"},1000).animate({"opacity":"1"},1000).animate({"opacity":"0.3"},1000).animate({"opacity":"1"},1000).animate({"opacity":"0.3"},1000).animate({"opacity":"1"},1000).animate({"opacity":"0.3"},1000).animate({"opacity":"0.7"},1000);
    scrollDownPlay = setInterval(function(){
        if(document.body.scrollTop > 0)
        {
            $("#scrollDown").hide();
            clearInterval(scrollDownPlay);
        }
    },50);
</script>