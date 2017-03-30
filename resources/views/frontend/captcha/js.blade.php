@if (!1)
<script>
@endif
@include('frontend.captcha.message')

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
                            console.log(a);
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
            xmlhttp.send(a.data);
        },
        _tx: null,
        pow: function(app){
            delete({{ $global_var }});

            (function(i,s,o,g,r,a,m){i['gxp']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.className='xtv';a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','{{ route('frontend.captcha.pow', Request::all() + ['v'=>'9999']) }}'.replace(/9999/g, function(){return (new Date).getTime();}),'xa');

            app._tx = setInterval(function(){
                if('undefined' == typeof {{ $global_var }}) return;
                clearInterval(app._tx);
                if(1 == {{ $global_var }}[3]){
                    app.challenge();
                    app.userverify({data:{}, answer:{}}, app);
                }  else {
                    $.id('l_captcha_widget').className = 'verify';
                    $.id('l_captcha_text').innerHTML = '点击此处进行人机识别验证';
                    app.fallback_load();
                }
                for(i = 0; i < 100000000; i++){
                    if($.md5((i * {{ $global_var }}[0]).toString()) == {{ $global_var }}[1]){
                        app.answer = i;
                        console.log(i);
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
                q: {{ $global_var }}[2],
                p: app.enc(app.answer, app.answer).replace(/[\r\n]/g, ''),
                m: app.enc(JSON.stringify(json.data), app.answer).replace(/[\r\n]/g, '')
            };
            if(json.type == 'VERIFY_FALLBACK'){
                data['a'] = 4;
                data['x'] = app.enc(JSON.stringify(app.fallback_answer), app.answer).replace(/[\r\n]/g, '')
            }
            $.ajax({
                url: '{{ route("frontend.captcha.userverify", \Request::all()) }}',
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function(a){
                    try{
                        app.messenger.targets['parent'].send(JSON.stringify(a));
                        $.id('l_captcha_status').className = $.id('l_captcha_status').className.replace(/ loading/g, '');
                        if(a.success){
                            $.id('l_captcha_widget').className = 'verify-success';
                            $.id('l_captcha_text').innerHTML = '恭喜! 您已通过验证';
                            return;
                        } else if (a.error_codes.length > 0 && a.error_codes[0] == 'FALLBACK_VERIFY_FAILED') {
                            if(a.error_codes.length > 1 && a.error_codes[1] == 'FALLBACK_REFRESH') {
                                app.fallback_refresh(app);
                            }
                            $.id('l_captcha_widget').className = 'verify-failed';
                            $.id('l_captcha_text').innerHTML = '验证失败!请重试';
                            return;
                        } else {
                            $.id('l_captcha_widget').className = 'verify';
                            $.id('l_captcha_text').innerHTML = '点击此处进行人机识别验证';
                            return;
                        }
                    }catch(e){
                        console.log(e);
                        alert('xCAPTCHA 初始化未成功');
                    }
                },
                error: function(a){console.log(a);}
            });
            delete(data);
        },
        fallback_init: function(app){
            // 在此处初始化 回落图形验证
            $.id('l_captcha_widget').addEventListener('click', function(e){
                app.messenger.targets['parent'].send(JSON.stringify({
                    success: false,
                    error_codes: ['OPEN_FALLBACK'],
                    callback: 'fallback_ready',
                    challenge: {
                        p: {{ $global_var }},
                        a: i
                    }
                }));
                $.id('l_captcha_status').className += ' loading';
                $.id('l_captcha_text').innerHTML = '加载中...';
            });
        },
        fallback_refresh: function(app){
            app.pow(app);
        },
        fallback_ready: function(app){
            $.id('l_captcha_status').className = $.id('l_captcha_status').className.replace(/ loading/g, '');
            $.id('l_captcha_text').innerHTML = '点击此处进行人机识别验证';
        },
        fallback_load: function(i){
            app.messenger.targets['parent'].send(JSON.stringify({
                success: false,
                error_codes: ['UPGRADE_CHALLENGE'],
                callback: '',
                data: {
                    challenge: {
                        p: {{ $global_var }},
                        a: i
                    }
                }
            }));

            app.fallback_init(app);
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
                    console.log('MSG REV:');
                    console.log(msg);
                });

                app.messenger.targets['parent'].send(JSON.stringify({
                    success: false,
                    error_codes: ['READY','INVISIBLE'],
                    callback: 'userverify'
                }));
            })(app);
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

@if (!1)
    </script>
@endif
