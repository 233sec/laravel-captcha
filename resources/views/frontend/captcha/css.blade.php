@if (!1)
<style>
@endif

html {
    color: #333
}

body,form,input,p,textarea {
    margin: 0;
    padding: 0
}

body,button,input,select,textarea {
    font: 400 12px/1.5 pingfang sc,lantinghei sc,hiragino sans gb,microsoft yahei,sans
}

img {
    border: 0;
    vertical-align: bottom
}

a,a:hover {
    text-decoration: none
}

body,html {
    background: none
}

#l_captcha_widget .captcha-widget-menu {
    cursor: pointer;
    font-size: 12px;
    height: 42px;
    zoom:1;position: relative
}

#l_captcha_widget .captcha-widget-menu:after,#l_captcha_widget .captcha-widget-menu:before {
    display: table;
    content: ''
}

#l_captcha_widget .captcha-widget-menu:after {
    clear: both
}

#l_captcha_widget .captcha-widget-event {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 42px
}

#l_captcha_widget .captcha-widget-copyright {
    position: absolute;
    right: 0;
    top: 0;
    width: 42px;
    bottom: 0
}

#l_captcha_widget .captcha-widget-copyright a {
    display: block;
    height: 30px;
    width: 42px;
    padding-top: 12px;
    position: relative;
    text-align: center
}

#l_captcha_widget .captcha-widget-copyright .copyright-icon {
    display: inline-block;
    width: 16px;
    height: 17px;
    background-repeat: no-repeat
}

#l_captcha_widget .captcha-widget-status {
    float: left;
    height: 33px;
    width: 42px;
    text-align: center;
    position: relative;
    padding-top: 9px
}

#l_captcha_widget .status-icon {
    display: inline-block;
    height: 23px;
    width: 23px;
    background-repeat: no-repeat
}

#l_captcha_widget .captcha-widget-text {
    float: left;
    line-height: 42px;
    height: 42px;
    font-size: 12px;
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none
}

#l_captcha_widget .captcha-widget-error {
    float: left;
    line-height: 42px;
    height: 42px;
    color: red;
    font-weight: 700;
    padding-left: 20px;
    padding-right: 20px;
    font-size: 12px
}

#l_captcha_widget.verify-success .status-icon {
    background-image: url(/img/captcha/widget_combine.png);
    background-position: -78px -23px
}

#l_captcha_widget.verify-failed .status-icon {
    background-image: url(/img/captcha/widget_combine.png);
    background-position: -78px 0
}

@media  only screen and (-o-min-device-pixel-ratio: 5/4),only screen and (-webkit-min-device-pixel-ratio:1.25),only screen and (min--moz-device-pixel-ratio:1.25),only screen and (min-device-pixel-ratio:1.25),only screen and (min-resolution:1.25dppx) {
    #l_captcha_widget.verify-failed .status-icon,#l_captcha_widget.verify-success .status-icon {
        background-image:url(/img/captcha/widget_2x_combine.png);
        background-size: 101px 46px
    }
}

.theme-default {
    border: 1px solid #d5d5d5;
    background: url(data:image/svg+xml;base64,pd94bwwgdmvyc2lvbj0ims4wiia/pgo8c3znihhtbg5zpsjodhrwoi8vd3d3lnczlm9yzy8ymdawl3n2zyigd2lkdgg9ijewmcuiighlawdodd0imtawjsigdmlld0jved0imcawidegmsigchjlc2vydmvbc3bly3rsyxrpbz0ibm9uzsi+ciagpgxpbmvhckdyywrpzw50iglkpsjncmfklxvjz2ctz2vuzxjhdgvkiibncmfkawvudfvuaxrzpsj1c2vyu3bhy2vpblvzzsigede9ijaliib5mt0imcuiihgypsiwjsigeti9ijewmcuipgogicagphn0b3agb2zmc2v0psiwjsigc3rvcc1jb2xvcj0ii2zmzmzmziigc3rvcc1vcgfjaxr5psixii8+ciagica8c3rvccbvzmzzzxq9ijewmcuiihn0b3aty29sb3i9iinmnmy2zjyiihn0b3atb3bhy2l0et0imsivpgogidwvbgluzwfyr3jhzgllbnq+ciagphjly3qged0imciget0imcigd2lkdgg9ijeiighlawdodd0imsigzmlsbd0idxjskcnncmfklxvjz2ctz2vuzxjhdgvkksiglz4kpc9zdmc+);
    background: -moz-linear-gradient(top,#fff 0,#f6f6f6 100%);
    background: -webkit-gradient(linear,left top,left bottom,color-stop(0,#fff),color-stop(100%,#f6f6f6));
    background: -webkit-linear-gradient(top,#fff 0,#f6f6f6 100%);
    background: -o-linear-gradient(top,#fff 0,#f6f6f6 100%);
    background: -ms-linear-gradient(top,#fff 0,#f6f6f6 100%);
    background: linear-gradient(to bottom,#fff 0,#f6f6f6 100%);
    filter: progid:dximagetransform.microsoft.gradient( startcolorstr='#ffffff', endcolorstr='#f6f6f6', gradienttype=0 );
    border-radius: 1px;
}

.theme-default:hover {
    background: url(data:image/svg+xml;base64,pd94bwwgdmvyc2lvbj0ims4wiia/pgo8c3znihhtbg5zpsjodhrwoi8vd3d3lnczlm9yzy8ymdawl3n2zyigd2lkdgg9ijewmcuiighlawdodd0imtawjsigdmlld0jved0imcawidegmsigchjlc2vydmvbc3bly3rsyxrpbz0ibm9uzsi+ciagpgxpbmvhckdyywrpzw50iglkpsjncmfklxvjz2ctz2vuzxjhdgvkiibncmfkawvudfvuaxrzpsj1c2vyu3bhy2vpblvzzsigede9ijaliib5mt0imcuiihgypsiwjsigeti9ijewmcuipgogicagphn0b3agb2zmc2v0psiwjsigc3rvcc1jb2xvcj0ii2y2zjzmniigc3rvcc1vcgfjaxr5psixii8+ciagica8c3rvccbvzmzzzxq9ijewmcuiihn0b3aty29sb3i9iinmzmzmzmyiihn0b3atb3bhy2l0et0imsivpgogidwvbgluzwfyr3jhzgllbnq+ciagphjly3qged0imciget0imcigd2lkdgg9ijeiighlawdodd0imsigzmlsbd0idxjskcnncmfklxvjz2ctz2vuzxjhdgvkksiglz4kpc9zdmc+);
    background: -moz-linear-gradient(top,#f6f6f6 0,#fff 100%);
    background: -webkit-gradient(linear,left top,left bottom,color-stop(0,#f6f6f6),color-stop(100%,#fff));
    background: -webkit-linear-gradient(top,#f6f6f6 0,#fff 100%);
    background: -o-linear-gradient(top,#f6f6f6 0,#fff 100%);
    background: -ms-linear-gradient(top,#f6f6f6 0,#fff 100%);
    background: linear-gradient(to bottom,#f6f6f6 0,#fff 100%);
    filter: progid:dximagetransform.microsoft.gradient( startcolorstr='#f6f6f6', endcolorstr='#ffffff', gradienttype=0 )
}

.theme-default .captcha-widget-text {
    color: #666
}

.theme-default .status-icon {
    background-position: 0 0
}

.theme-default .captcha-widget-copyright .copyright-icon {
    background-position: -46px -29px
}

#l_captcha_widget .theme-default .loading .status-icon,.theme-default .captcha-widget-copyright .copyright-icon,.theme-default .status-icon {
    background-image: url(/img/captcha/widget_combine.png)
}

#l_captcha_widget.verify-success .theme-default .captcha-widget-text {
    color: #27ae60
}

#l_captcha_widget.verify-failed .theme-default .captcha-widget-text {
    color: #e74c3c
}

#l_captcha_widget .theme-default .loading .status-icon {
    background-image: url(/img/captcha/widget_combine.png);
    background-size: 101px 46px;
    background-position: 0 -23px;
}

@media  only screen and (-o-min-device-pixel-ratio: 5/4),only screen and (-webkit-min-device-pixel-ratio:1.25),only screen and (min--moz-device-pixel-ratio:1.25),only screen and (min-device-pixel-ratio:1.25),only screen and (min-resolution:1.25dppx) {
    #l_captcha_widget .theme-default .loading .status-icon,
    .theme-default .captcha-widget-copyright .copyright-icon,.theme-default .status-icon {
        background-image:url(/img/captcha/widget_2x_combine.png);
        background-size: 101px 46px
    }
}

.theme-dark {
    background: #414653;
    border: 1px solid #414653;
}

.theme-dark .captcha-widget-text {
    color: #FFF
}

.theme-dark .status-icon {
    background-position: -46px 0
}

.theme-dark .captcha-widget-copyright .copyright-icon {
    background-position: -62px -29px
}

#l_captcha_widget .theme-dark .loading .status-icon,.theme-dark .captcha-widget-copyright .copyright-icon,.theme-dark .status-icon {
    background-image: url(/img/captcha/widget_combine.png)
}

#l_captcha_widget.verify-success .theme-dark .captcha-widget-text {
    color: #40d47e
}

#l_captcha_widget.verify-failed .theme-dark .captcha-widget-text {
    color: #e74c3c
}

#l_captcha_widget .theme-dark .loading .status-icon {
    background-position: -23px -23px
}

@media  only screen and (-o-min-device-pixel-ratio: 5/4),only screen and (-webkit-min-device-pixel-ratio:1.25),only screen and (min--moz-device-pixel-ratio:1.25),only screen and (min-device-pixel-ratio:1.25),only screen and (min-resolution:1.25dppx) {
    #l_captcha_widget .theme-dark .loading .status-icon,.theme-dark .captcha-widget-copyright .copyright-icon,.theme-dark .status-icon {
        background-image:url(/img/captcha/widget_2x_combine.png);
        background-size: 101px 46px
    }
}

.theme-light {
    border-radius: 1px;
    background: #F1F1F1;
    border: 1px solid #F1F1F1;
}

.theme-light .captcha-widget-text {
    color: #666
}

.theme-light .status-icon {
    background-position: -23px 0
}

.theme-light .captcha-widget-copyright .copyright-icon {
    background-position: -46px -29px
}

#l_captcha_widget .theme-light .loading .status-icon,.theme-light .captcha-widget-copyright .copyright-icon,.theme-light .status-icon {
    background-image: url(/img/captcha/widget_combine.png)
}

#l_captcha_widget.verify-success .theme-light .captcha-widget-text {
    color: #27ae60
}

#l_captcha_widget.verify-failed .theme-light .captcha-widget-text {
    color: #e74c3c
}

#l_captcha_widget .theme-light .loading .status-icon {
    background-position: 0 -23px
}

@media  only screen and (-o-min-device-pixel-ratio: 5/4),only screen and (-webkit-min-device-pixel-ratio:1.25),only screen and (min--moz-device-pixel-ratio:1.25),only screen and (min-device-pixel-ratio:1.25),only screen and (min-resolution:1.25dppx) {
    #l_captcha_widget .theme-light .loading .status-icon,.theme-light .captcha-widget-copyright .copyright-icon,.theme-light .status-icon {
        background-image:url(/img/captcha/widget_2x_combine.png);
        background-size: 101px 46px
    }
}

.theme-transparent {
    border-radius: 1px;
    background: 0 0;
    border: 1px solid #F1F1F1;
}

.theme-transparent .captcha-widget-text {
    color: #FFF
}

.theme-transparent .status-icon {
    background-position: -46px 0
}

.theme-transparent .captcha-widget-copyright .copyright-icon {
    background-position: -62px -29px
}

#l_captcha_widget .theme-transparent .loading .status-icon,.theme-transparent .captcha-widget-copyright .copyright-icon,.theme-transparent .status-icon {
    background-image: url(/img/captcha/widget_combine.png)
}

#l_captcha_widget.verify-success .theme-transparent .captcha-widget-text {
    color: #40d47e
}

#l_captcha_widget.verify-failed .theme-transparent .captcha-widget-text {
    color: #e74c3c
}

#l_captcha_widget .theme-transparent .loading .status-icon {
    background-position: -23px -23px
}

@media  only screen and (-o-min-device-pixel-ratio: 5/4),only screen and (-webkit-min-device-pixel-ratio:1.25),only screen and (min--moz-device-pixel-ratio:1.25),only screen and (min-device-pixel-ratio:1.25),only screen and (min-resolution:1.25dppx) {
    #l_captcha_widget .theme-transparent .loading .status-icon,.theme-transparent .captcha-widget-copyright .copyright-icon,.theme-transparent .status-icon {
        background-image:url(/img/captcha/widget_2x_combine.png);
        background-size: 101px 46px
    }
}


@if (!1)
</style>
@endif
