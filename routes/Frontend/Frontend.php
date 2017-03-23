<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', 'FrontendController@index')->name('index');

Route::get('/recaptcha/api2/userverify', 'FrontendController@macros')->name('zh');                              # user verify
Route::get('/recaptcha/api2/siteverify', 'FrontendController@macros')->name('zh');                              # site verify
Route::get('/recaptcha/api.js', 'CaptchaController@api')->name('loader');                                       # api.js
Route::get('/recaptcha/api2/r20170315121834/recaptcha__zh_cn.js', 'FrontendController@macros')->name('zh');     # recaptcha.js
Route::get('/recaptcha/api2/r20170315121834/styles__ltr.css', 'FrontendController@macros')->name('zh');         # style.css
Route::get('/recaptcha/api2/anchor', 'FrontendController@macros')->name('anchor');                              # 角标

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
