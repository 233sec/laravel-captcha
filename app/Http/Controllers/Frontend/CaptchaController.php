<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Response;

/**
 * Class CaptchaController.
 */
class CaptchaController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function anchor()
    {
        return view('frontend.captcha.anchor');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function userverify()
    {
        # 分析POW 100 分
        # 分析鼠标轨迹 65 分以上
        # 分析百度的IP
        # 分析行为间隔
        
        return Response::json([
            'head' => ['status' => 0, 'note' => 'SUCCESS'],
            'body' => ['challenge_response' => 'ZGZnc2doZmdmajU2NGRnZmhnZjMyNA=='],
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function siteverify()
    {
        return Response::json([
            'success' => true,
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function css()
    {
        return view('frontend.index');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function js()
    {
        return view('frontend.index');
    }
}
