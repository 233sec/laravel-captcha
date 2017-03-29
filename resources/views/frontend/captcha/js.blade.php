@if (!1)
<script>
@endif
@include('frontend.captcha.message')

(function(a){
    var $ = {
        md5: md5,
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
        init: function(){
            var messenger = new Messenger('xcaptcha_frame', 'xCAPTCHA');

            messenger.listen(function (msg) {
                console.log('MSG REV:');
                console.log(msg);
            });

            messenger.addTarget(window.parent, 'parent');

            for(i = 0; i < 100000; i++){
                if($.md5((i * {{ $global_var }}[0]).toString()) == {{ $global_var }}[1]){
                    $.ajax({
                        url: '{{ route("frontend.captcha.userverify", \Request::all()) }}',
                        data: {
                            q: {{ $global_var }}[2],
                            p: i
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function(a){
                            try{
                                messenger.targets['parent'].send(JSON.stringify(a));
                            }catch(e){
                                alert('xCAPTCHA 初始化未成功');
                            }
                        },
                        error: function(a){console.log(a);}
                    })
                    break;
                }
            }

        }
    };
    $.init();
})(window);

@if (!1)
    </script>
@endif
