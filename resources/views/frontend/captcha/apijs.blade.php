@if (!1)
<script>
@endif

window._anchor_url = '{{ route('frontend.captcha.anchor', ["k" => "_3_3_4_5_"]) }}';
window._fallback_url_a = '{!! route('frontend.captcha.fallback', ["k" => "_3_3_4_5_", "q" => 9999, "a" => 9998]) !!}';
window._fallback_url_b = '{!! route("frontend.captcha.fallback", ["k"=>9999, "q"=>9998, "a"=>9997]) !!}';
@include('frontend.captcha.message_ugly')
@include('frontend.captcha.apijs_ugly')

@if (!1)
    </script>
@endif
