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
        userverify: function(json, app){
            for(i = 0; i < 100000; i++){
                if($.md5((i * {{ $global_var }}[0]).toString()) == {{ $global_var }}[1]){
                    $.ajax({
                        url: '{{ route("frontend.captcha.userverify", \Request::all()) }}',
                        data: {
                            q: {{ $global_var }}[2],
                            p: app.enc(i, i).replace(/[\r\n]/g, ''),
                            m: app.enc(JSON.stringify(json.data), i).replace(/[\r\n]/g, '')
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function(a){
                            try{
                                console.log(a);
                                if(a.success){
                                    $.id('l_captcha_widget').className = 'verify-success';
                                    $.id('l_captcha_text').innerHTML = '验证成功!';
                                } else if (a.error_codes.length > 0 && a.error_codes[0] == 'FALLBACK_VERIFY_FAILED') {
                                    $.id('l_captcha_widget').className = 'verify-failed';
                                    $.id('l_captcha_text').innerHTML = '验证失败!请重试';
                                } else {
                                    $.id('l_captcha_widget').className = 'verify';
                                    $.id('l_captcha_text').innerHTML = '点击此处进行人机识别验证';
                                }
                                app.messenger.targets['parent'].send(JSON.stringify(a));
                            }catch(e){
                                alert('xCAPTCHA 初始化未成功');
                            }
                        },
                        error: function(a){console.log(a);}
                    })
                    break;
                }
            }
        },
        init: function(){
            this.messenger = new Messenger('xcaptcha_frame', 'xCAPTCHA');

            this.messenger.addTarget(window.parent, 'parent');

            app = this;
            (function(app){

                if(1 == {{ $global_var }}[3]){
                    setTimeout(function(){
                        app.challenge();
                    }, 1000);
                }  else {
                    app.messenger.targets['parent'].send(JSON.stringify({
                        success: false,
                        error_codes: ['UPGRADE_CHALLENGE'],
                        callback: ''
                    }));
                }

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
