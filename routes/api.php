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

Route::post('/recaptcha/api2/userverify', '\App\Http\Controllers\Frontend\CaptchaController@userverify')->name('frontend.captcha.userverify');             # user verify
Route::get('/recaptcha/api2/siteverify', '\App\Http\Controllers\Frontend\CaptchaController@siteverify')->name('frontend.captcha.siteverify');              # site verify

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
