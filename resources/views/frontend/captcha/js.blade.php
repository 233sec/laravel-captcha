@if (!1)
<script>
@endif

window._pow_url = "{!! route('frontend.captcha.pow', Request::all() + ['v'=>'9999']) !!}";
window._uv_url = '{{ route("frontend.captcha.userverify", \Request::all()) }}';

@include('frontend.captcha.message_ugly')
@include('frontend.captcha.js_ugly')

@if (!1)
    </script>
@endif
