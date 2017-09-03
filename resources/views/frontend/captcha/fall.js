var messenger = new Messenger('xcaptcha_frame_fall', 'xCAPTCHA');
messenger.addTarget(window.parent, 'parent');

messenger.targets['parent'].send(JSON.stringify({
    success: false,
    error_codes: ['READY_FALLBACK'],
    callback: ''
}));

$.id('lc_captcha_list').addEventListener('click', function(e){
    messenger.targets['parent'].send(JSON.stringify({
        success: false,
        error_codes: ['VERIFY_FALLBACK'],
        callback: 'userverify',
        type: 'FALLBACK',
        data: {
            pos: {
                x: e.pageX,
                y: e.pageY
            }
        }
    }));
});
messenger.listen(function (msg) {
    try{
        var json = JSON.parse(msg);
        if(json.success){
            $.id('lc_success_overlay').style.display = 'block';
            $.id('lc_fail_overlay').style.display = 'none';
            setTimeout(function(){
                messenger.targets['parent'].send(JSON.stringify({
                    success: false,
                    error_codes: ['CLOSE_FALLBACK'],
                    type: 'FALLBACK',
                }));
            }, 1000);
        }else{
            $.id('lc_fail_overlay').style.display = 'block';
            $.id('lc_success_overlay').style.display = 'none';
            try{clearTimeout(window._lo);}catch(e){}
            window._lo = setTimeout(function(){
                $.id('lc_fail_overlay').style.display = 'none';
            }, 1000);
        }
    }catch(e){
    }
});
