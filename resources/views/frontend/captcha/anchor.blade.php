<!DOCTYPE HTML>
<html><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Luosimao Captcha</title>
    <link type="text/css" rel="stylesheet" href="{{ route('frontend.captcha.css') }}">
</head>
<body>
<div class="verify" id="l_captcha_widget">
    <div class="captcha-widget-menu theme-dark">
        <span class="captcha-widget-copyright">
            <a href="javascript:" id="l_captcha_link" title="永利宝人机验证服务"><i class="copyright-icon"></i></a>
        </span>
        <div class="captcha-widget-event">
            <span class="captcha-widget-status" id="l_captcha_status">
                <i class="status-icon"></i>
            </span>
            <span class="captcha-widget-text" id="l_captcha_text">点击此处进行人机识别验证</span>
        </div>
    </div>
</div>
<script src="{{ route('frontend.captcha.pow', Request::all() + ['v'=>'_'.time()]) }}"></script>
<script src="{{ route('frontend.captcha.js', Request::all()) }}"></script>
</body></html>
