<!DOCTYPE HTML>
<html><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>xCAPTCHA</title>
    <link type="text/css" rel="stylesheet" href="{{ route('frontend.captcha.css') }}">
</head>
<body>
<div class="@if ($errors->any()) verify-failed @else verify @endif" id="l_captcha_widget">
    <div class="captcha-widget-menu theme-{{ $theme }}">
        <span class="captcha-widget-copyright">
            <a href="javascript:" id="l_captcha_link" title="xCAPTCHA"><i class="copyright-icon"></i></a>
        </span>
        <div class="captcha-widget-event">
            <span class="captcha-widget-status @if (!$errors->any()) loading @endif" id="l_captcha_status">
                <i class="status-icon"></i>
            </span>
            <span class="captcha-widget-text" id="l_captcha_text">
                @if ($errors->any())
                    {{ implode('', $errors->all(':message')) }}
                @else
                    加载中
                @endif
            </span>
        </div>
    </div>
</div>
@if (!$errors->any())
    <script src="{{ route('frontend.captcha.js', Request::all()) }}"></script>
@endif
</body></html>
