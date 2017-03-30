@if (!1)
<script>
@endif
@include('frontend.captcha.message')

var messenger = new Messenger('xcaptcha_frame_fall', 'xCAPTCHA');
messenger.addTarget(window.parent, 'parent');

messenger.targets['parent'].send(JSON.stringify({
    success: false,
    error_codes: ['READY_FALLBACK'],
    callback: ''
}));

$.id('lc-image-panel').addEventListener('click', function(e){
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
@if (!1)
    </script>
@endif
