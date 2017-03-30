@if (!1)
<script>
@endif
if('undefined' !== typeof {{ $global_var }}) delete({{ $global_var }});
{{ $global_var }} = [{{$challenge[0]}}, '{{$challenge[1]}}', '{{$challenge[2]}}', {{$challenge[4]}}];
@if (!1)
</script>
@endif
