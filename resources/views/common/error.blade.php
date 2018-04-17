<script>
    @if(count($errors) > 0)
        alert('验证失败！');
    @foreach ($errors->all() as $error)
        console.log("{{ $error }}");
    @endforeach
@endif
@if(Session::has('msg'))
alert("{{ Session::pull('msg') }}");
@endif
</script>