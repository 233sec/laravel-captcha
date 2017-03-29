<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('/recaptcha/api2/demo', '\App\Http\Controllers\Frontend\CaptchaController@demo')->name('frontend.captcha.demo');                                    # 角标
Route::get('/recaptcha/P0W.js', '\App\Http\Controllers\Frontend\CaptchaController@pow')->name('frontend.captcha.pow');                                      # api.js
Route::get('/recaptcha/api.js', '\App\Http\Controllers\Frontend\CaptchaController@apiJs')->name('frontend.captcha.loader');                                    # api.js
Route::get('/recaptcha/api2/r20170315121834/message.js', '\App\Http\Controllers\Frontend\CaptchaController@messageJs')->name('frontend.captcha.message');      # recaptcha.js
Route::get('/recaptcha/api2/r20170315121834/recaptcha__zh_cn.js', '\App\Http\Controllers\Frontend\CaptchaController@js')->name('frontend.captcha.js');         # recaptcha.js
Route::get('/recaptcha/api2/r20170315121834/styles__ltr.css', '\App\Http\Controllers\Frontend\CaptchaController@css')->name('frontend.captcha.css');           # style.css
Route::get('/recaptcha/api2/anchor', '\App\Http\Controllers\Frontend\CaptchaController@anchor')->where('size', 'invisible')->name('frontend.captcha.anchor');  # 角标
Route::get('/recaptcha/api2/anchor', '\App\Http\Controllers\Frontend\CaptchaController@anchor')->where('size', 'nocaptcha')->name('frontend.captcha.anchor');  # 角标
Route::any('/recaptcha/api2/userverify', '\App\Http\Controllers\Frontend\CaptchaController@userverify')->name('frontend.captcha.userverify');                  # user verify
Route::any('/recaptcha/api2/siteverify', '\App\Http\Controllers\Frontend\CaptchaController@siteverify')->name('frontend.captcha.siteverify');                  # site verify
