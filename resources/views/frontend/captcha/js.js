(function(a){
    var $ = {
        id: function(id){
            try{
                return document.getElementById(id);
            }catch(e){
                return window[id];
            }
        },
        md5: md5,
        messenger: null,
        enc: function(t, k){
            return GibberishAES.enc(t, k);
        },
        dec: function(e, k){
            return GibberishAES.dec(e, k);
        },
        ajax: function(a){
            'undefined' == a.success ? a.success = function(a){} : 0;
            'undefined' == a.error ? a.error = function(a){} : 0;
            'undefined' == a.complete ? a.complete = function(a){} : 0;

            var xmlhttp=null;
            if (window.XMLHttpRequest) {// code for all new browsers
                xmlhttp=new XMLHttpRequest();
            } else if (window.ActiveXObject) {// code for IE5 and IE6
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            if (xmlhttp==null) return alert("Your browser does not support XMLHTTP.");

            xmlhttp.onreadystatechange = function(){
                if('string' == typeof a.dataType && a.dataType.toLowerCase() == 'jsonp'){
                    var key = 'jsonpCallback_'+parseInt(Math.random() * 999999);
                    window[key] = function(b){a.success(b);};
                    if('string' == typeof a.data) a.data += '&'+a.jsonpCallback+'='+key;
                    else if('object' == typeof a.data) a.data['jsonpCallback'] = key;

                    delete(key);
                }
                if (xmlhttp.readyState==4) {// 4 = "loaded"
                    if (xmlhttp.status==200) {// 200 = OK
                        if('string' == typeof a.dataType && a.dataType.toLowerCase() == 'json'){
                            try{
                                eval('var json='+xmlhttp.responseText);
                                a.success(json, xmlhttp.status, xmlhttp);
                            }catch(e){
                                a.error(xmlhttp.status, xmlhttp.responseText);
                            }
                        }else if('string' == typeof a.dataType && a.dataType.toLowerCase() == 'script'){
                            eval(xmlhttp.responseText);
                        }else{
                            a.success(xmlhttp.responseText, xmlhttp.status, xmlhttp);
                        }
                    } else if(xmlhttp.status != 0) {
                        a.error(xmlhttp.status, xmlhttp.responseText);
                    }
                }
            };
            xmlhttp.open('undefined' == typeof a.type ? 'GET' : a.type, a.url, 'undefined' == typeof a.async ? true : !1);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            if('object' == typeof a.data){
                s = '';
                for(i in a.data) s += encodeURIComponent(i) + '=' + encodeURIComponent(a.data[i]) + '&';

                s = s.substr(0, s.length-1);
                a.data = s;
            }

            setTimeout(function(){
                xmlhttp.send(a.data);
            }, 1);
        },
        _tx: null,
        pow: function(app){
            delete(window[window.__recaptcha_tk]);

            (function(i,s,o,g,r,a,m){i['gxp']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.className='xtv';a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script',window._pow_url.replace(/9999/g, function(){return (new Date).getTime();}),'xa');

            app = this;
            app._tx = setInterval(function(){
                if('undefined' == typeof window[window.__recaptcha_tk]) return;
                clearInterval(app._tx);
                for(i = 0; i < 100000000; i++){
                    if($.md5((i * window[window.__recaptcha_tk][0]).toString()) == window[window.__recaptcha_tk][1]){
                        app.answer = i;
                        if(1 == window[window.__recaptcha_tk][3]){
                            app.challenge();
                            $.id('l_captcha_status').className = $.id('l_captcha_status').className.replace(/ loading/g, '');
                            $.id('l_captcha_widget').className = 'verify-success';
                            $.id('l_captcha_text').innerHTML = '??????! ??????????????????';
                        }  else {
                            $.id('l_captcha_status').className = $.id('l_captcha_status').className.replace(/ loading/g, '');
                            $.id('l_captcha_widget').className = 'verify';
                            $.id('l_captcha_text').innerHTML = '????????????????????????????????????';
                            app.fallback_load(app);
                        }
                        break;
                    }
                }
            }, 100);

            return;
        },
        answer: -1,
        fallback_answer: '',
        userverify: function(json, app){
            var data = {
                a: 9,
                q: app.enc(window[window.__recaptcha_tk][2], window.__recaptcha_tk).replace(/[\r\n]/g, ''),
                p: app.enc(app.answer, app.answer).replace(/[\r\n]/g, ''),
                m: app.enc(JSON.stringify(json.data), app.answer).replace(/[\r\n]/g, '')
            };
            if(json.type == 'VERIFY_FALLBACK'){
                data['a'] = 4;
                data['x'] = app.enc(JSON.stringify(app.fallback_answer), app.answer).replace(/[\r\n]/g, '')
            }
            app = this;
            $.ajax({
                url: window._uv_url,
                data: data,
                type: 'POST',
                dataType: 'text',
                success: function(a){
                    try{
                        a = JSON.parse(app.dec(a, app.answer)); 
                        app.messenger.targets['parent'].send(JSON.stringify(a));
                        if(a.success){
                            $.id('l_captcha_status').className = $.id('l_captcha_status').className.replace(/ loading/g, '');
                            $.id('l_captcha_widget').className = 'verify-success';
                            $.id('l_captcha_text').innerHTML = '??????! ??????????????????';
                            return;
                        } else if (a.error_codes.length > 0 && a.error_codes[0] == 'FALLBACK_VERIFY_FAILED') {
                            if(a.error_codes.length > 1 && a.error_codes[1] == 'FALLBACK_REFRESH') {
                                app.fallback_refresh(app);
                            }
                            $.id('l_captcha_widget').className = 'verify-failed';
                            $.id('l_captcha_text').innerHTML = '????????????!?????????';
                            try{clearTimeout(window._lo);}catch(e){}
                            window._lo = setTimeout(function(){
                                $.id('l_captcha_status').className = $.id('l_captcha_status').className.replace(/ loading/g, '');
                                $.id('l_captcha_widget').className = 'verify';
                                $.id('l_captcha_text').innerHTML = '????????????????????????????????????';
                                if(app.fallback_c_status != 1){
                                    app.fallback_init();
                                }
                            }, 1000);
                            return;
                        } else if(a.error_codes.length > 0 && a.error_codes[0] == 'INVISIBLE_PLEASE') {
                            // ??????????????? invisible ??????
                            app.messenger.targets['parent'].send(JSON.stringify({
                                success: false,
                                error_codes: ['CLOSE_FALLBACK']
                            }));
                        } else {
                            $.id('l_captcha_status').className = $.id('l_captcha_status').className.replace(/ loading/g, '');
                            $.id('l_captcha_widget').className = 'verify';
                            $.id('l_captcha_text').innerHTML = '????????????????????????????????????';
                            return;
                        }
                    }catch(e){
                        alert('xCAPTCHA ??????????????????');
                    }
                },
                error: function(a){console.log(a);}
            });
            delete(data);
        },
        fallback_init: function(app){
            // ?????????????????? ??????????????????
            app = this;

            $.id('l_captcha_widget').addEventListener('click', function(e){
                if($.id('l_captcha_widget').className.indexOf('-success') > -1) return;
                app.messenger.targets['parent'].send(JSON.stringify({
                    success: false,
                    error_codes: ['OPEN_FALLBACK'],
                    callback: 'fallback_ready',
                    challenge: {
                        p: window[window.__recaptcha_tk],
                        a: app.answer
                    }
                }));
                $.id('l_captcha_status').className += ' loading';
                $.id('l_captcha_text').innerHTML = '?????????...';
            });
        },
        fallback_refresh: function(app){
            app.pow(this);
        },
        fallback_c_status: 0,
        fallback_ready: function(app){
            app.fallback_c_status = 1;
            $.id('l_captcha_status').className = $.id('l_captcha_status').className.replace(/ loading/g, '');
            $.id('l_captcha_widget').className = 'verify';
            $.id('l_captcha_text').innerHTML = '????????????????????????????????????';
        },
        fallback_load: function(app){
            app.messenger.targets['parent'].send(JSON.stringify({
                success: false,
                error_codes: ['UPGRADE_CHALLENGE'],
                callback: '',
                data: {
                    challenge: {
                        p: window[window.__recaptcha_tk],
                        a: app.answer
                    }
                }
            }));

            app.fallback_init(this);
        },
        init: function(){
            this._status_ok == 0;
            this.messenger = new Messenger('xcaptcha_frame', 'xCAPTCHA');
            this.messenger.addTarget(window.parent, 'parent');

            app = this;
            (function(app){
                app.pow(app);
                app.messenger.listen(function (msg) {
                    try{
                        var json = JSON.parse(msg);

                        if('string' == typeof json.callback && 'function' == typeof app[json.callback]){
                            app[json.callback](json, app);
                        }
                    }catch(e){}
                });

                app.messenger.targets['parent'].send(JSON.stringify({
                    success: false,
                    error_codes: ['READY','FALLBACK']
                }));
            })(this);
        },
        challenge: function(){
            this.messenger.targets['parent'].send(JSON.stringify({
                success: false,
                error_codes: ['POST_MOUSE'],
                callback: 'userverify'
            }));
        }
    };
    $.init();
})(window);
