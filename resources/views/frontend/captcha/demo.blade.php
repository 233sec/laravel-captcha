@extends('frontend.layouts.app')

@section('content')
<style>
*, .btn, .form-control{
    border-radius: 0!important;
}
</style>

@section('content')
<div class="col-xs-12">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">演示</div>
            <div class="panel-body">
                <div class="col-md-4 col-md-offset-4">
                    <div class="row">
                        <form id="recaptcha-demo-form" method="POST">
                            <div class="form-group">
                                <label> 手机号 </label>
                                <input class="form-control" type="number" name="tel">
                            </div>
                            <div class="form-group">
                                <div id="recaptcha-demo" class="g-recaptcha" data-sitekey="{{ getenv('XCAPTCHA_KEY') }}" data-auto-callback="onAuto" data-usercheck-callback="showCaptcha" data-callback="onSuccess" data-needcheck-callback="showCaptcha" data-bind="recaptcha-demo-submit"></div>
                            </div>
                            <button class="btn btn-success" id="recaptcha-demo-submit" type="submit">短信验证</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@endsection

@section('after-scripts')
<script src="{{ route('frontend.captcha.loader') }}"></script>
<script>
var onSuccess=function(a){
};
var onAuto=function(a){
    $('#recaptcha-demo').hide();
    return onSuccess();
};


var showCaptcha=function(a){
    $('#recaptcha-demo').show();
};
</script>
@endsection
