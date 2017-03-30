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
        document.getElementsByClassName('g-recaptcha')[0].innerHTML += '<iframe id="xcaptcha_frame" style="display:none;border: 0px;width: 100%;height: 44px;" src="{{ route('frontend.captcha.anchor', ["k" => "_3_3_4_5_"]) }}">'.replace(/_3_3_4_5_/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey'));

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
            }else if(json.success == false && json.error_codes[0] == 'READY' && json.error_codes[1] == 'INVISIBLE'){
                try{ document.getElementById('xcaptcha_frame').style.display = 'block'; }catch(e){ xcaptcha_frame.style.display = 'block'; }
            }else if(json.success == false && json.error_codes[0] == 'READY' && json.error_codes[1] == 'FALLBACK'){
                try{ document.getElementById('xcaptcha_frame').style.display = 'block'; }catch(e){ xcaptcha_frame.style.display = 'block'; }
            }else if(json.success == false && json.error_codes[0] == 'OPEN_FALLBACK'){
                // 回落验证 点击事件
                try{ $.id('xcaptcha_frame_fallback').remove(); }catch(e){}

                var ifrm = document.createElement("iframe");
                ifrm.setAttribute("id", "xcaptcha_frame_fallback");
                ifrm.setAttribute("src", "{{ route('frontend.captcha.fallback', ["k" => "_3_3_4_5_"]) }}".replace(/_3_3_4_5_/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey')));
                ifrm.setAttribute("style", "z-index: 2000000001; position: relative; width: 300px; height: 190px; background: rgb(255, 255, 255);display: block; border: 1px solid rgb(204, 204, 204); border-radius: 2px; box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 3px; position: absolute; z-index: 2000000000; visibility: visible;");

                var pos = $.id('xcaptcha_frame').getBoundingClientRect();
                var top = pos.top - 200;
                var left = pos.left;

                ifrm.style.left = left + 'px';
                ifrm.style.top = top + 'px';

                delete(pos);
                delete(top);
                delete(left);

                document.body.appendChild(ifrm);
                window.__l_c = json.callback

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
