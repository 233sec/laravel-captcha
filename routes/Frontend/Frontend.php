<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', 'FrontendController@index')->name('index');
Route::get('macros', 'FrontendController@macros')->name('macros');

Route::get('/recaptcha/api2/userverify', 'CaptchaController@userverify')->name('zh');                              # user verify
Route::get('/recaptcha/api2/siteverify', 'CaptchaController@siteverify')->name('zh');                              # site verify
Route::get('/recaptcha/api.js', 'CaptchaController@api')->name('captcha.loader');                                  # api.js
Route::get('/recaptcha/api2/r20170315121834/recaptcha__zh_cn.js', 'CaptchaController@js')->name('captcha.js');     # recaptcha.js
Route::get('/recaptcha/api2/r20170315121834/styles__ltr.css', 'CaptchaController@css')->name('captcha.css');       # style.css
Route::get('/recaptcha/api2/anchor', 'CaptchaController@anchor')->name('captcha.anchor');                          # 角标

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */
Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        /*
         * User Dashboard Specific
         */
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        /*
         * User Account Specific
         */
        Route::get('account', 'AccountController@index')->name('account');

        /*
         * User Profile Specific
         */
        Route::patch('profile/update', 'ProfileController@update')->name('profile.update');
    });
});
