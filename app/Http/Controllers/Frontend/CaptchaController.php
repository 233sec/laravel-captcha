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
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->somethingElseIsInvalid()) {
                $validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });
    }

    /**
     * @return \Illuminate\View\View
     */
    public function demo(Request $request)
    {
        if($request->method() == 'POST'){
            return dump($request->input());
        }
        return view('frontend.captcha.demo');
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
            return Response::json([ 'success' => false, 'error_codes' => ['INVALID_APPKEY'], ]);
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
            return Response::jsonp('console.error', json_encode([ 'success' => false, 'error_codes' => ['INVALID_APPKEY'] ]));

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
        # 分析行为间隔 1.anchor5分 2.pow15分 3.userverify15分
        #
        # 若满分75分以上 通过

        $appkey = $request->get('k', null);
        $score = 0;

        if(!$appkey)
            return Response::json(['success' => false, 'error_codes' => ['INVALID_APPKEY'], ]);

        $p = $request->input('p');
        $q = $request->input('q');
        $q = json_decode(decrypt($q));

        if(!$q[0] || !$q[1] || !$q[2])
            return Response::json(['success' => false, 'error_codes' => ['INVALID_POW']]);

        if($q[2] != $q[0] * $p)
            return Response::json(['success' => false, 'error_codes' => ['INVALID_POW_ANSWER']]);

        $ua = $request->server('HTTP_USER_AGENT');
        $ua = parse_user_agent($ua);

        # 如果是正常的浏览器
        # 
        # 加20分

        if(in_array($ua['platform'], ['iOS', 'Android', 'Macintosh', 'Linux', 'Windows', 'NT', 'Windows NT']) && in_array($ua['browser'], ['Chrome', 'Safari', 'IE', 'ie', 'Sogou', 'SogouExplorer', '360', '360 Browser']) && 
            isset($ua['version']) )
        {
            $score += 20;
        }

        # IP 如果在5分钟内验证次数在5次以内
        # 加20分
        # 
        # IP 如果非IDC机房IP
        # 加10分
        #
        # IP 如果百度能定位到
        # 加10分
        #
        # SESS_ID 如果在5分钟内验证次数在5分钟内
        # 加10分
        if(1)
        {
            $score += 20;
        }
        if(1)
        {
            $score += 10;
        }
        if(1)
        {
            $score += 10;
        }
        if(1)
        {
            $score += 10;
        }

        # 如果是桌面
        if(!in_array($ua['platform'], ['iOS', 'Android']))
        {
            # 鼠标移动轨迹
            if(1) # 如果轨迹合理
            {
                if(2) # 如果没有重复
                {
                    $score += 20;
                }
                $score += 10;
            }
        }
        # 如果是手机
        else
        {
            $score += 10;
        }

        #请求顺序
        if('anchor')
        {
            $score += 5;
        }
        if('pow')
        {
            $score += 15;
        }
        if('userverify')
        {
            $score += 15;
        }

        $challenge_response = gmdate('YmdHis') . substr(mt_rand(100000000, 999999999), 1);
        return Response::json([
            'success' => true,
            'challenge_ts' => gmdate('Y-m-d\TH:i:s\Z'),
            'hostname' => 'www.baidu.com',
            'error_codes' => [],
            'response' => encrypt($challenge_response)
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function siteverify(Request $request)
    {
        try
        {
            if($request->method() == 'POST')
            {
                $method = 'input';
            }
            else
            {
                $method = 'query';
            }

            $appkey    = $request->$method('k',        null);
            $appsecret = $request->$method('secret',   null);
            $response  = $request->$method('response', null);
            $remoteip  = $request->$method('remoteip', null);

            $this->validate($request, [
                'k' => 'required|min:10|max:40',
                'secret' => 'required|min:10|max:40',
                'response' => 'required|min:40|max:256',
                'remoteip' => 'optional|min:7|max:15'
            ]);

            # 验证appkey 和 appsecret
            # 验证response
            # 验证response 和 appkey
            # 验证response 和 remoteip

            return Response::json([
                'success' => true,
                'challenge_ts' => gmdate('Y-m-d\TH:i:s\Z'),
                'hostname' => 'www.baidu.com',
                'error_codes' => [] 
            ]);
        }
        catch(\Exception $e)
        {
            return Response::json([
                'success' => false,
                'error_codes' => [$e->getMessage()] 
            ]);
        }
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
    public function apiJs()
    {
        return response()->view('frontend.captcha.apijs', ['global_var' => $this->g])->withHeaders(['Content-Type' => 'application/x-javascript']);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function js()
    {
        return response()->view('frontend.captcha.js', ['global_var' => $this->g])->withHeaders(['Content-Type' => 'application/x-javascript']);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function messageJs()
    {
        return response()->view('frontend.captcha.message', ['global_var' => $this->g])->withHeaders(['Content-Type' => 'application/x-javascript']);
    }
}
