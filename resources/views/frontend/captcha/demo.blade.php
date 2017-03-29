<!DOCTYPE HTML>
<html dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,user-scalable=yes">
<title>ReCAPTCHA 演示</title>
<link rel="stylesheet" href="https://www.gstatic.cn/recaptcha/api2/r20170315121834/demo__ltr.css" type="text/css">
</head>
<body>
<div class="sample-form">
	<form id="recaptcha-demo-form" method="POST">
		<fieldset>
			<legend>具有 ReCAPTCHA 保护功能的示例表单</legend>
			<ul>
				<li><label for="input1">名字</label><input class="jfk-textinput" id="input1" name="input1" type="text" value="Jane"></li>
				<li><label for="input2">姓氏</label><input class="jfk-textinput" id="input2" name="input2" type="text" value="Smith"></li>
				<li><label for="input3">电子邮件</label><input class="jfk-textinput" id="input3" name="input3" type="text" value="stopallbots@gmail.com"></li>
				<li>
				<p>
					选择您喜爱的颜色：
				</p>
				<label class="jfk-radiobutton-label" for="option1"><input class="jfk-radiobutton-checked" type="radio" id="option1" name="radios" value="option1" checked aria-checked="true">红色</label><label class="jfk-radiobutton-label" for="option2"><input class="jfk-radiobutton" type="radio" id="option2" name="radios" value="option2" disabled aria-disabled="true">绿色</label></li>
				<li>
				<div class="">
					<div id="recaptcha-demo" class="g-recaptcha" data-sitekey="6LfP0CITAAAAAHq9FOgCo7v_fb0-pmmH9VW3ziFs" data-callback="onSuccess" data-bind="recaptcha-demo-submit">
					</div>
				</div>
				</li>
				<li><button id="recaptcha-demo-submit" type="submit" disabled>提交</button></li>
			</ul>
		</fieldset>
	</form>
</div>
<script src="{{ route('frontend.captcha.loader') }}"></script>
<script>
var onSuccess=function(a){
    if(a.success == true)
        window['recaptcha-demo-submit'].removeAttribute('disabled');
};
</script>
</body>
</html>
