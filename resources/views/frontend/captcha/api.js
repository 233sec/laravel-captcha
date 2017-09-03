(function(a){
    try{
        // xCAPTCHA loading begin
        window._p = []; window._p_l = 0;
        document.getElementsByClassName('g-recaptcha')[0].innerHTML = '';
        document.getElementsByClassName('g-recaptcha')[0].innerHTML += '<input type="hidden" name="g-recaptcha-response" value="" id="g-recaptcha-response">';
        document.getElementsByClassName('g-recaptcha')[0].innerHTML += ('<iframe id="xcaptcha_frame" style="border: 0px;width: 100%;height: 44px;" src="'+window._anchor_url+'">').replace(/_3_3_4_5_/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey'));

        window.messenger = new Messenger('parent', 'xCAPTCHA');
        window.messenger.addTarget($.id('xcaptcha_frame').contentWindow, 'xcaptcha_frame');

        document.addEventListener('mousemove', function(e){
            if(window._p_l > (new Date).getTime()) return;
            window._p_l = (new Date).getTime() + 50;

            if(window._p[window._p.length - 1] != {x:e.screenX, y:e.screenY})
                window._p.push({x:e.screenX, y:e.screenY});
        });

        var _g_captcha_callback = document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-callback');
        var _g_captcha_uc_callback = document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-needcheck-callback');
        var _g_button = document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-bind');
        // xCAPTCHA loading end
        
        window.onresize = function(){
            try{
                var pos = $.id('xcaptcha_frame').getBoundingClientRect();
                var width = pos.width;
                var top = pos.top - 200 + window.scrollY;
                if(top < 170)
                    top = pos.top + pos.height + 8;

                var left = pos.left + window.scrollX;
                if(width > 300)
                    left += (width - 300)/2;

                $.id('xcaptcha_frame_fallback').style.left = left + 'px';
                $.id('xcaptcha_frame_fallback').style.top = top + 'px';

                delete(pos);
                delete(top);
                delete(left);
            }catch(e){}
        };

        messenger.listen(function (msg) {
            json = JSON.parse(msg);
            if(json.success == true){
                // xCAPTCHA passed
                window['g-recaptcha-response'].value = json.response;
                if('undefined' !== typeof messenger.targets['xcaptcha_frame_fall']){
                    messenger.targets['xcaptcha_frame_fall'].send(JSON.stringify(json));
                }
                if('function' == typeof window[_g_captcha_callback]) window[_g_captcha_callback](json);
                if(json.error_codes[0] == 'INVISIBLE'){
                    try{
                        if($.id(_g_button).className.indexOf('binded') == -1) return;
                        $.id(_g_button).removeAttribute('onclick');
                        $.id(_g_button).click();
                    }catch(e){}
                }
            }else if(json.success == false && json.error_codes[0] == 'READY' && json.error_codes[1] == 'INVISIBLE'){
            }else if(json.success == false && json.error_codes[0] == 'READY' && json.error_codes[1] == 'FALLBACK'){
                try{  _g_captcha_uc_callback(json); }catch(e){}
            }else if(json.success == false && json.error_codes[0] == 'CLOSE_FALLBACK'){
                try{
                    $.id('xcaptcha_frame_fallback').style.display = 'none';
                    $.id('xcaptcha_frame_overlay').style.display = 'none';
                    messenger.targets['xcaptcha_frame'].send(JSON.stringify({
                        action: 'READY_INVISIBLE',
                        callback: 'pow'
                    }));
                }catch(e){}
            }else if(json.success == false && json.error_codes[0] == 'INVALID_POW'){
                // 无效pow 重新加载pow
                messenger.targets['xcaptcha_frame'].send(JSON.stringify({
                    action: 'INVALID_POW',
                    callback: 'pow'
                }));
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
                    ifrm.setAttribute("src", window._fallback_url_a.replace(/_3_3_4_5_/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey')).replace(/9999/g, json.challenge.p[2]).replace(/9998/g, json.challenge.a));
                    ifrm.setAttribute("style", "z-index: 2000000001; position: relative; width: 300px; height: 190px; background: rgb(255, 255, 255);display: block; border: 1px solid rgb(204, 204, 204); border-radius: 2px; box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 3px; position: absolute; z-index: 2000000000; visibility: visible;");
                    document.body.appendChild(ifrm);

                    window.__l_c = json.callback;
                    window.messenger.addTarget(ifrm.contentWindow, 'xcaptcha_frame_fall');

                    $.id('xcaptcha_frame_fallback').style.display = 'block';
                    $.id('xcaptcha_frame_overlay').style.display = 'block';
                }

                window.onresize();

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
                if($.id('xcaptcha_frame_fallback') != null)
                    $.id('xcaptcha_frame_fallback').src = window._fallback_url_b.replace(/9999/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey')).replace(/9998/, json.data.challenge.p[2]).replace(/9997/, json.data.challenge.a)

                if('undefined' !== typeof _g_captcha_uc_callback)
                    if('undefined' !== typeof window[_g_captcha_uc_callback])
                        window[_g_captcha_uc_callback](json);
            }else if(json.success == false && json.error_codes[0] == 'POST_MOUSE'){
                // 给提交按钮绑定事件
                if('undefined' !== typeof _g_button)
                    try{
                        if($.id(_g_button).className.indexOf('binded') > -1) return;
                        $.id(_g_button).addEventListener('mousedown', function(event){
                            messenger.targets['xcaptcha_frame'].send(JSON.stringify({
                                action: 'POST_MOUSE',
                                data: window._p,
                                callback: 'userverify'
                            }));
                            event.stopPropagation();
                            event.preventDefault();
                            return !1;
                        });
                        $.id(_g_button).setAttribute('onclick', 'return false');
                        $.id(_g_button).className = $.id(_g_button).className + ' binded';
                    }catch(e){}
            }else if(json.success == false && json.error_codes[0] == 'FALLBACK_VERIFY_FAILED'){
                if('undefined' == typeof messenger.targets['xcaptcha_frame_fall'])
                    messenger.targets['parent'].send(JSON.stringify({
                        error_codes: ['OPEN_FALLBACK'],
                    }));
                messenger.targets['xcaptcha_frame_fall'].send(JSON.stringify(json));
            }
            return;
        });

        window.xCAPTCHA = {
            reset: function(){
                messenger.targets['xcaptcha_frame'].send(JSON.stringify({
                    callback: 'init'
                }));
            }
        };
    }catch(e){
        console.log(e);
    }

})(window);

