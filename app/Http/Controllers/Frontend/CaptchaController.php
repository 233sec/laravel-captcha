<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

/**
 * Class CaptchaController.
 */
class CaptchaController extends Controller
{
    protected $g;

    public function __construct(\Request $request){
        if (getenv('APP_DEBUG')==true && class_exists('\Debugbar') ) \Debugbar::disable();
        $ip = \Request::ip();
        $this->g = '_c'.md5($ip);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function anchor(Request $request)
    {
        # 加载完成需加载POW算题
        # 运算完POW算题

        $appkey = $request->get('k', null);

        if(!$appkey)
            return Response::json([ 'success' => false, 'error-codes' => ['INVALID_APPKEY'], ]);
        return view('frontend.captcha.anchor');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function pow(Request $request)
    {
        # POW算题

        $appkey = $request->get('k', null);

        if(!$appkey)
            return Response::jsonp('console.error', json_encode([ 'success' => false, 'error-codes' => ['INVALID_APPKEY'] ]));

        $factor_one = (int)    substr(mt_rand(100000, 999999), 1);
        $factor_two = (int)    substr(mt_rand(100000, 999999), 1);
        $factor_tri = (int)    $factor_one * $factor_two;
        $factor_hax = (string) md5($factor_tri);
        $factor_cga = (string) encrypt(json_encode([$factor_one, $factor_two, $factor_tri, $factor_hax]));

        $challenge  = (array)  [$factor_one, $factor_hax, $factor_cga, $factor_two];

        return response()->view('frontend.captcha.pow', ['challenge' => $challenge, 'global_var' => $this->g])->withHeaders(['Content-Type' => 'application/x-javascript']);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function userverify(Request $request)
    {
        # 分析POW 1.对10分 2.错-100分
        # 分析鼠标轨迹 1.非全直5分 2.非全折5分 3.历史无重合10分
        # 分析百度的IP 1.有20分 2.没有0分
        # 分析行为间隔 1.anchor5分 2.pow15分 3.worker15分 4.userverify15分
        #
        # 若满分75分以上 通过

        $appkey = $request->get('k', null);

        if(!$appkey)
            return Response::json([ 'success' => false, 'error-codes' => ['INVALID_APPKEY'], ]);

        return Response::json([
            'success' => true,
            'challenge_ts' => gmdate('Y-m-d\TH:i:s\Z'),
            'hostname' => 'www.baidu.com',
            'error-codes' => [],
            'response' => encrypt('10086')
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function siteverify()
    {
        return Response::json([
            'success' => true,
            'challenge_ts' => gmdate('Y-m-d\TH:i:s\Z'),
            'hostname' => 'www.baidu.com',
            'error-codes' => [] 
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function css()
    {
        return response()->view('frontend.captcha.css', ['global_var' => $this->g])->withHeaders(['Content-Type' => 'text/css']);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function js()
    {
        return response()->view('frontend.captcha.js', ['global_var' => $this->g])->withHeaders(['Content-Type' => 'application/x-javascript']);
    }
}
