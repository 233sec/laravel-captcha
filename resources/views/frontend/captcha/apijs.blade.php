@if (!1)
<script>
@endif

@include('frontend.captcha.message')

(function(a){
    try{
        // xCAPTCHA loading begin
        window._p = []; window._p_l = 0;
        document.getElementsByClassName('g-recaptcha')[0].innerHTML = '';
        document.getElementsByClassName('g-recaptcha')[0].innerHTML += '<input type="hidden" name="g-recaptcha-response" value="" id="g-recaptcha-response">';
        document.getElementsByClassName('g-recaptcha')[0].innerHTML += '<iframe id="xcaptcha_frame" style="display:block;border: 0px;width: 100%;height: 44px;" src="{{ route('frontend.captcha.anchor', ["k" => "_3_3_4_5_"]) }}">'.replace(/_3_3_4_5_/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey'));

        window.messenger = new Messenger('parent', 'xCAPTCHA');
        window.messenger.addTarget(xcaptcha_frame.contentWindow, 'xcaptcha_frame');

        document.addEventListener('mousemove', function(e){
            if(window._p_l > (new Date).getTime()) return;
            window._p_l = (new Date).getTime() + 50;

            if(window._p[window._p.length - 1] != {x:e.screenX, y:e.screenY})
                window._p.push({x:e.screenX, y:e.screenY});
        });

        var _g_captcha_callback = document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-callback');
        // xCAPTCHA loading end

        messenger.listen(function (msg) {
            json = JSON.parse(msg);
            if(json.success == true){
                // xCAPTCHA passed
                window['g-recaptcha-response'].value = json.response;
                if('undefined' !== typeof messenger.targets['xcaptcha_frame_fall']){
                    messenger.targets['xcaptcha_frame_fall'].send(JSON.stringify(json));
                }
            }else if(json.success == false && json.error_codes[0] == 'READY' && json.error_codes[1] == 'INVISIBLE'){
                try{ document.getElementById('xcaptcha_frame').style.display = 'block'; }catch(e){ xcaptcha_frame.style.display = 'block'; }
            }else if(json.success == false && json.error_codes[0] == 'READY' && json.error_codes[1] == 'FALLBACK'){
                try{ document.getElementById('xcaptcha_frame').style.display = 'block'; }catch(e){ xcaptcha_frame.style.display = 'block'; }
            }else if(json.success == false && json.error_codes[0] == 'CLOSE_FALLBACK'){
                try{
                    $.id('xcaptcha_frame_fallback').style.display = 'none';
                    $.id('xcaptcha_frame_overlay').style.display = 'none';
                }catch(e){}
            }else if(json.success == false && json.error_codes[0] == 'OPEN_FALLBACK'){
                // 回落验证 点击事件
                try{
                    $.id('xcaptcha_frame_fallback').style.display = 'block';
                    $.id('xcaptcha_frame_overlay').style.display = 'block';
                    messenger.targets['xcaptcha_frame'].send(JSON.stringify({
                        action: 'READY_FALLBACK',
                        callback: window.__l_c
                    }));
                }catch(e){
                    var overlay = document.createElement("div");
                    overlay.setAttribute("style", "z-index: 1000000001;position: fixed;width: 100%;height: 100%;background: transparent;top: 0;");
                    overlay.setAttribute("id", "xcaptcha_frame_overlay");
                    overlay.addEventListener('click', function(){
                        try{ $.id('xcaptcha_frame_fallback').style.display = 'none'; overlay.style.display = 'none'; ifrm.style.display = 'none'; }catch(e){}
                    });
                    document.body.appendChild(overlay);

                    var ifrm = document.createElement("iframe");
                    ifrm.setAttribute("id", "xcaptcha_frame_fallback");
                    ifrm.setAttribute("src", "{!! route('frontend.captcha.fallback', ["k" => "_3_3_4_5_", "q" => 9999, "a" => 9998]) !!}".replace(/_3_3_4_5_/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey')).replace(/9999/g, json.challenge.p[2]).replace(/9998/g, json.challenge.a));
                    ifrm.setAttribute("style", "z-index: 2000000001; position: relative; width: 300px; height: 190px; background: rgb(255, 255, 255);display: block; border: 1px solid rgb(204, 204, 204); border-radius: 2px; box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 3px; position: absolute; z-index: 2000000000; visibility: visible;");

                    var pos = $.id('xcaptcha_frame').getBoundingClientRect();
                    var width = pos.width;
                    var top = pos.top - 200 + window.scrollY;
                    var left = pos.left + window.scrollX;
                    if(width > 300)
                        left += (width - 300)/2;


                    ifrm.style.left = left + 'px';
                    ifrm.style.top = top + 'px';

                    delete(pos);
                    delete(top);
                    delete(left);

                    document.body.appendChild(ifrm);
                    window.__l_c = json.callback;
                    window.messenger.addTarget(ifrm.contentWindow, 'xcaptcha_frame_fall');

                    $.id('xcaptcha_frame_fallback').style.display = 'block';
                    $.id('xcaptcha_frame_overlay').style.display = 'block';
                }

            }else if(json.success == false && json.error_codes[0] == 'READY_FALLBACK'){
                messenger.targets['xcaptcha_frame'].send(JSON.stringify({
                    action: 'READY_FALLBACK',
                    callback: window.__l_c
                }));
            }else if(json.success == false && json.error_codes[0] == 'VERIFY_FALLBACK'){
                messenger.targets['xcaptcha_frame'].send(JSON.stringify({
                    action: 'POST_MOUSE',
                    data: json.data,
                    error_codes: ['VERIFY_FALLBACK'],
                    callback: json.callback
                }));
            }else if(json.success == false && json.error_codes[0] == 'UPGRADE_CHALLENGE'){
                // try{ document.getElementById('xcaptcha_frame').src='{{ route('frontend.captcha.anchor', ["k" => "_3_3_4_5_"]) }}'.replace(/_3_3_4_5_/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey')); }catch(e){ xcaptcha_frame.src='{{ route('frontend.captcha.anchor', ["k" => "_3_3_4_5_"]) }}'.replace(/_3_3_4_5_/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey')); }
            }else if(json.success == false && json.error_codes[0] == 'POST_MOUSE'){
                messenger.targets['xcaptcha_frame'].send(JSON.stringify({
                    action: 'POST_MOUSE',
                    data: window._p,
                    callback: json.callback
                }));
            }else if(json.success == false && json.error_codes[0] == 'FALLBACK_VERIFY_FAILED'){
                messenger.targets['xcaptcha_frame_fall'].send(JSON.stringify(json));
            }
            if('function' == typeof window[_g_captcha_callback]) window[_g_captcha_callback](json);
            return;
        });
    }catch(e){

    }

})(window);

@if (!1)
    </script>
@endif
