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
        document.getElementsByClassName('g-recaptcha')[0].innerHTML += '<iframe id="xcaptcha_frame" style="box-shadow: grey 0px 0px 5px;border: 0;width: 255px;height: 60px;position: fixed;bottom: 15px;right: 0;" src="{{ route('frontend.captcha.anchor', ["k" => "_3_3_4_5_"]) }}">'.replace(/_3_3_4_5_/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey'));

        window.messenger = new Messenger('parent', 'xCAPTCHA');
        window.messenger.addTarget(xcaptcha_frame.contentWindow, 'xcaptcha_frame');

        document.addEventListener('mousemove', function(e){
            if(window._p_l > (new Date).getTime()) return;
            window._p_l = (new Date).getTime() + 50;

            if(window._p[window._p.length - 1] != {x:e.screenX, y:e.screenY})
                window._p.push({x:e.screenX, y:e.screenY});
        });


        var callback = document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-callback');
        // xCAPTCHA loading end

        messenger.listen(function (msg) {
            json = JSON.parse(msg);
            if(json.success == true){
                // xCAPTCHA passed
                window['g-recaptcha-response'].value = json.response;
            }else if(json.success == false && json.error_codes[0] == 'UPGRADE_CHALLENGE'){
                // xCAPTCHA noCAPTCHA
            }else if(json.success == false && json.error_codes[0] == 'POST_MOUSE'){
                messenger.targets['xcaptcha_frame'].send(JSON.stringify({
                    action: 'POST_MOUSE',
                    data: window._p,
                    callback: json.callback
                }));
            }
            if('function' == typeof window[callback]) window[callback](json);
            return;
        });
    }catch(e){

    }

})(window);

@if (!1)
    </script>
@endif
