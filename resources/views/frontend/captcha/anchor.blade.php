<!DOCTYPE HTML>
<html dir="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" type="text/css" href="{{ route('frontend.captcha.css', Request::all()) }}">
    </head>
    <body style="">
        <div class="rc-anchor-alert">
        </div>
        <input type="hidden" id="recaptcha-token" value="">
        <div class="rc-anchor rc-anchor-invisible rc-anchor-invisible-hover">
            <div class="rc-anchor-aria-status">
                <section><span id="recaptcha-accessible-status" aria-live="assertive" aria-atomic="true">xCaptcha 要求验证</span></section>
            </div>
            <div class="rc-anchor-error-msg-container" style="display:none">
                <span class="rc-anchor-error-msg"></span>
            </div>
            <div class="rc-anchor-normal-footer smalltext">
                <div class="rc-anchor-logo-large" role="presentation">
                    <div class="rc-anchor-logo-img rc-anchor-logo-img-large">
                    </div>
                </div>
                <div class="rc-anchor-pt">
                    <a href="http://www.google.cn/intl/zh-CN/policies/privacy/" target="_blank">隐私权</a><span aria-hidden="true" role="presentation"> - </span><a href="http://www.google.cn/intl/zh-CN/policies/terms/" target="_blank">使用条款</a>
                </div>
            </div>
            <div class="rc-anchor-invisible-text">
                <span>由 <strong>xCAPTCHA</strong> 提供保护</span>
                <div class="rc-anchor-pt">
                    <a href="http://www.google.cn/intl/zh-CN/policies/privacy/" target="_blank">隐私权</a><span aria-hidden="true" role="presentation"> - </span><a href="http://www.google.cn/intl/zh-CN/policies/terms/" target="_blank">使用条款</a>
                </div>
            </div>
        </div>
        <script src="{{ route('frontend.captcha.pow', Request::all() + ['v'=>'_'.time()]) }}"></script>
        <script src="{{ route('frontend.captcha.js', Request::all()) }}"></script>
    </body>
</html>
