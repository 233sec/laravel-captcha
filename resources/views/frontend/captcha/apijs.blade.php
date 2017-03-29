@if (!1)
<script>
@endif

@include('frontend.captcha.message')

(function(a){
    try{
        var messenger = new Messenger('parent', 'xCAPTCHA');
        // xCAPTCHA loading begin
        document.getElementsByClassName('g-recaptcha')[0].innerHTML = '';
        document.getElementsByClassName('g-recaptcha')[0].innerHTML += '<input type="hidden" name="g-recaptcha-response" value="" id="g-recaptcha-response">';
        document.getElementsByClassName('g-recaptcha')[0].innerHTML += '<iframe id="xcaptcha_frame" style="box-shadow: grey 0px 0px 5px;border: 0;width: 255px;height: 60px;position: fixed;bottom: 15px;right: 0;" src="{{ route('frontend.captcha.anchor', ["k" => "_3_3_4_5_"]) }}">'.replace(/_3_3_4_5_/, document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-sitekey'));

        var callback = document.getElementsByClassName('g-recaptcha')[0].getAttribute('data-callback');
        // xCAPTCHA loading end
        messenger.addTarget(xcaptcha_frame.contentWindow, 'xcaptcha_frame');
        messenger.listen(function (msg) {
            json = JSON.parse(msg);
            if(json.success == true){
                // xCAPTCHA passed
                window['g-recaptcha-response'].value = json.response;
            }else if(json.success == false && json.error-codes == ['UPGRADE_CHALLENGE']){
                // xCAPTCHA noCAPTCHA
            }else{
                // xCAPTCHA CHALLENGE FAIL
            }

            if('function' == typeof window[callback]) window[callback](json);
            return;
        });

        messenger.targets[xcaptcha_frame].send("message from parent: " + msg);
    }catch(e){

    }

})(window);

@if (!1)
    </script>
@endif
