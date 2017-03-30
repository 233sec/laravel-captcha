<!DOCTYPE html>
<html><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUOTEST</title>
    <link type="text/css" rel="stylesheet" href="//s-captcha.luosimao.com/static/dist/captcha_frame.css?v=201610101436.css">
</head>
<body>
<div class="lc-panel">
    <div class="lc-header"></div>
    <div class="lc-main">
        <div class="lc-image-container" id="lc-image-panel">
            <div class="lc-success-overlay" id="lc_success_overlay">
                <div class="overlay-panel">
                    <i class="overlay-icon"></i>
                </div>
                <div class="overlay"></div>
            </div>
            <div class="lc-fail-overlay" id="lc_fail_overlay">
                <div class="overlay-panel">
                    <i class="overlay-icon"></i>
                </div>
                <div class="overlay"></div>
            </div>
            <div class="captcha-list" style="background-image: url(&quot;{{ route('frontend.captcha.fallimg', \Request::all()) }}&quot;);">
            </div>
        </div>
    </div>
    <div class="lc-footer">
        <div class="lc-controls" id="lc-controls">
            <span class="lc-button lc-refresh" title="刷新验证"><i></i></span>
            <a class="lc-button lc-sign" title="帮助" href="javascript:void(0);"><i></i></a>
        </div>
        <div class="lc-status-word"><span class="word-text" id="lc-captcha-word">请点击上方图片中 <i>方块</i></span></div>
    </div>
</div>
<script src="{{ route('frontend.captcha.falljs') }}"></script>
</body></html>
