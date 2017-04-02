<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Response;
use Image;
use DB;

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
            $response = $request->input('g-recaptcha-response');
            return response()->redirectToRoute('frontend.captcha.siteverify', ['k' => "6LfP0CITAAAAAHq9FOgCo7v_fb0-pmmH9VW3ziFs", 'secret' => "6LfP0CITAAAAAHq9FOgCo7v_fb0-pmmH9VW3ziFs", 'response' => $response]);
        }
        return view('frontend.captcha.demo');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function anchor(Request $request)
    {
        $appkey = $request->get('k', null);
        if(!$appkey)
            return Response::json([ 'success' => false, 'error_codes' => ['INVALID_APPKEY'], ]);

        $app = json_decode(Redis::get('APP:KEY:'.$appkey));
        if(!$app)
        {
            $app = DB::table('app')->where(['key' => $appkey])->first();
            if(!$app)
                return Response::json([ 'success' => false, 'error_codes' => ['INVALID_APPKEY'], ]);

            Redis::set('APP:KEY:'.$appkey, json_encode($app));
        }

        if(!$app->theme)
            $app->theme = 'default';

        return view('frontend.captcha.anchor', ['theme' => $app->theme]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function fall(Request $request)
    {
        $appkey = $request->get('k', null);
        if(!$appkey)
            return Response::json([ 'success' => false, 'error_codes' => ['INVALID_APPKEY'], ]);
        return view('frontend.captcha.fall');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function fallback(Request $request)
    {
        $appkey = $request->get('k', null);
        if(!$appkey)
            return Response::json([ 'success' => false, 'error_codes' => ['INVALID_APPKEY'], ]);
        return view('frontend.captcha.fallback');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function fallbackCss(Request $request)
    {
        return response()->view('frontend.captcha.frame_css')->withHeaders(['Content-Type' => 'text/css']);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function fallimage(Request $request)
    {
        $appkey = $request->get('k', null);
        if(!$appkey)
            return Response::json([ 'success' => false, 'error_codes' => ['INVALID_APPKEY'], ]);

        $p = $request->query('a');
        $q = $request->query('q');
        $q = json_decode(decrypt($q));

        #if(!$q[0] || !$q[1] || !$q[2] || !Redis::del('POW:'.$q[0].':'.$p))
        #    return Response::json(['success' => false, 'error_codes' => ['INVALID_POW']]);

        if($q[2] != $q[0] * $p)
            return Response::json(['success' => false, 'error_codes' => ['INVALID_POW_ANSWER']]);

        $x = mt_rand(10, 270);
        $y = mt_rand(10, 130);

        $ip = $request->ip();
        $id = $request->session()->getId();

        Redis::setex('FALLBACK:x:'.$ip.':'.$id, 900, $x);
        Redis::setex('FALLBACK:y:'.$ip.':'.$id, 900, $y);
        Redis::setex('Q2:'.$q[2].':'.$q[0], 86400, 1);

        $img = Image::make(app_path().'/../resources/assets/image/captcha/bg/'.mt_rand(1,4).'.png');

        $img->circle(25, 9 + $x, 9 + $y, function ($draw) {
            $draw->background(array(200, 200, 200, 0.33));
        });
        $img->circle(23, 9 + $x, 9 + $y, function ($draw) {
            $draw->background(array(150, 150, 150, 0.2));
        });
        $img->rectangle(4 + $x, 4 + $y, 14 + $x, 14 + $y, function ($draw) {
            $draw->background(array(255, 255, 255));
        });

        return $img->response('png');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function pow(Request $request)
    {
        # POW算题

        $ip = $request->ip();
        $id = $request->session()->getId();

        $appkey = $request->get('k', null);

        if(!$appkey)
            return Response::jsonp('console.error', json_encode([ 'success' => false, 'error_codes' => ['INVALID_APPKEY'] ]));

        if($request->query('d') == 1)
            return Response::json(['RATE:IPID:'.$ip.':'.$id]);

        Redis::del('FALLBACK:c:'.$ip.':'.$id, 1);

        $q1 = Redis::get('RATE:IP:'.$ip);
        $q2 = Redis::get('RATE:ID:'.$id);
        $q3 = Redis::get('RATE:IPID:'.$ip.':'.$id);

        $x1 = Redis::get('NOT:IP:'.$ip);
        $x2 = Redis::get('NOT:ID:'.$id);
        $x3 = Redis::get('NOT:IPID:'.$ip.':'.$id);

        $factor_one = (int)    substr(mt_rand(100, 9999999), 1);
        $factor_two = (int)    substr(mt_rand(10000, 99999), 1);
        $factor_tri = (int)    $factor_one * $factor_two;
        $factor_fou = (int)    ($q1 > 30 || $q2 > 10 || $q3 > 5 || $x1 >= 5 || $x2 >= 4 || $x3 >= 3) ? 0 : 1; # 是否invisible验证
        $factor_hax = (string) md5($factor_tri);
        $factor_cga = (string) encrypt(json_encode([$factor_one, $factor_two, $factor_tri, $factor_hax, $factor_fou]));

        $challenge  = (array)  [$factor_one, $factor_hax, $factor_cga, $factor_two, $factor_fou];

        # Cache set factor_one && factor_two
        Redis::setex('POW:'.$factor_one.':'.$factor_two, 600, 1);

        return response()->view('frontend.captcha.pow', ['challenge' => $challenge, 'global_var' => $this->g])->withHeaders(['Content-Type' => 'application/x-javascript']);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function userverify(Request $request)
    {
        try{
            # 分析POW 1.对10分 2.错-100分
            # 分析鼠标轨迹 1.非全直5分 2.非全折5分 3.历史无重合10分
            # 分析百度的IP 1.有20分 2.没有0分
            # 分析行为间隔 1.anchor5分 2.pow15分 3.userverify15分
            #
            # 若满分75分以上 通过

            if($request->method() != 'POST')
                throw new \Exception(json_encode(['success' => false, 'error_codes' => ['POST_ONLY'], ]), 1);

            $appkey = $request->get('k', null);
            $score = 0;

            if(!$appkey)
                throw new \Exception(json_encode(['success' => false, 'error_codes' => ['INVALID_APPKEY'], ]), 1);

            $ip = $request->ip();
            $id = $request->session()->getId();

            $q1 = Redis::get('RATE:IP:'.$ip);
            $q2 = Redis::get('RATE:ID:'.$id);
            $q3 = Redis::get('RATE:IPID:'.$ip.':'.$id);

            $x1 = Redis::get('NOT:IP:'.$ip);
            $x2 = Redis::get('NOT:ID:'.$id);
            $x3 = Redis::get('NOT:IPID:'.$ip.':'.$id);

            if($q1 > 30 || $q2 > 10 || $q3 > 5 || $x1 > 5 || $x2 > 4 || $x3 > 3) # 回落验证
            {
                $q = $request->input('q');
                $q = \GibberishAES\GibberishAES::dec($q, $this->g);
                $q = json_decode(decrypt($q));

                $p = $request->input('p');
                $p = \GibberishAES\GibberishAES::dec($p, $q[1]);

                $m = $request->input('m');
                $m = \GibberishAES\GibberishAES::dec($m, $q[1]);

                $ip = $request->ip();
                $id = $request->session()->getId();
                Redis::incr('FALLBACK:c:'.$ip.':'.$id);
                $x = Redis::get('FALLBACK:x:'.$ip.':'.$id);
                $y = Redis::get('FALLBACK:y:'.$ip.':'.$id);
                $c = Redis::get('FALLBACK:c:'.$ip.':'.$id);

                $data = json_decode($m, 1);

                if(!isset($data['pos']) || !isset($data['pos']['x']) || !isset($data['pos']['y']))
                    throw new \Exception(json_encode(['success' => false, 'error_codes' => ['FALLBACK_VERIFY_FAILED', 'BAD_PARAM']]), 1);

                if(abs($data['pos']['x'] - $x) > 19 || abs($data['pos']['y'] - $y) > 19)
                {
                    if($c >= 3)
                    {
                        throw new \Exception(json_encode(['success' => false, 'error_codes' => ['FALLBACK_VERIFY_FAILED', 'FALLBACK_REFRESH' ]]), 1);
                    }
                    throw new \Exception(json_encode(['success' => false, 'error_codes' => ['FALLBACK_VERIFY_FAILED' ]]), 1);
                }
                else if(!Redis::del('Q2:'.$q[2].':'.$q[0]))
                {
                    throw new \Exception(json_encode(['success' => false, 'error_codes' => ['FALLBACK_VERIFY_FAILED', 'BAD_POW']]), 1);
                }
                else # 成功
                {
                    $score += 10000;
                    Redis::set('RATE:IP:'.$ip, 1);
                    Redis::set('RATE:ID:'.$id, 1);
                    Redis::set('RATE:IPID:'.$ip.':'.$id, 1);

                    $type = 'FALLBACK';
                }
            }
            else # 隐藏验证
            {
                $q = $request->input('q');
                $q = \GibberishAES\GibberishAES::dec($q, $this->g);
                $q = json_decode(decrypt($q));

                $p = $request->input('p');
                $p = \GibberishAES\GibberishAES::dec($p, $q[1]);

                if(!$q[0] || !$q[1] || !$q[2] || !Redis::del('POW:'.$q[0].':'.$p))
                    throw new \Exception(json_encode(['success' => false, 'error_codes' => ['INVALID_POW']]), 1);

                if($q[2] != $q[0] * $p)
                    throw new \Exception(json_encode(['success' => false, 'error_codes' => ['INVALID_POW_ANSWER']]), 1);

                # Cache del factor_one && factor_two

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
                if(0) # 如果是 IDC 的 IP 段
                {
                    $score -= 10000;
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
                $type = 'INVISIBLE';
            }

            $challenge_response = gmdate('YmdHis') . substr(mt_rand(100000000, 999999999), 1);

            Redis::set('CHALLENGE:RESPONSE:'.$challenge_response, 1);

            Redis::expire('CHALLENGE:RESPONSE:'.$challenge_response, 900);

            Redis::set('IP:RESPONSE:'.$challenge_response, $ip);
            Redis::set('ID:RESPONSE:'.$challenge_response, $id);

            Redis::expire('IP:RESPONSE:'.$challenge_response, 900);
            Redis::expire('ID:RESPONSE:'.$challenge_response, 900);

            Redis::incr('NOT:IP:'.$ip);
            Redis::incr('NOT:ID:'.$id);
            Redis::incr('NOT:IPID:'.$ip.':'.$id);

            Redis::expire('NOT:IP:'.$ip, 600);
            Redis::expire('NOT:ID:'.$id, 600);
            Redis::expire('NOT:IPID:'.$ip.':'.$id, 600);

            throw new \Exception(json_encode([
                'success' => true,
                'challenge_ts' => gmdate('Y-m-d\TH:i:s\Z'),
                'hostname' => 'www.baidu.com',
                'error_codes' => [$type],
                'response' => encrypt($challenge_response)
            ]), 1);
        }catch(\Exception $e){
            // return response()->make($e->getMessage().$e->getLine())->withHeaders(['Content-Type' => 'text/plain']);
            return response()->make(\GibberishAES\GibberishAES::enc($e->getMessage(), $q[1]))->withHeaders(['Content-Type' => 'text/plain']);
        }
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
                'k'        => 'required|min:10|max:40',
                'secret'   => 'required|min:10|max:40',
                'response' => 'required|min:40|max:256',
                'remoteip' => 'nullable|ip'
            ]);

            # 验证appkey 和 appsecret
            # 验证response
            # 验证response 和 appkey
            # 验证response 和 remoteip
            $app = json_decode(Redis::get('APP:KEY:'.$appkey));
            if(!$app)
            {
                $app = DB::table('app')->where(['key' => $appkey])->first();
                if(!$app)
                    return Response::json([ 'success' => false, 'error_codes' => ['INVALID_APPKEY'], ]);

                Redis::set('APP:KEY:'.$appkey, json_encode($app));
            }
            if($app->secret != $appsecret)
                return Response::json([ 'success' => false, 'error_codes' => ['INVALID_APPSECRET'], ]);

            $response = decrypt($response);

            $do = Redis::get('CHALLENGE:RESPONSE:'.$response);
            $ip = Redis::get('IP:RESPONSE:'.$response);
            $id = Redis::get('ID:RESPONSE:'.$response);

            Redis::incr('RATE:IP:'.$ip);           Redis::expire('RATE:IP:'.$ip, 600);
            Redis::incr('RATE:ID:'.$id);           Redis::expire('RATE:ID:'.$id, 600);
            Redis::incr('RATE:IPID:'.$ip.':'.$id); Redis::expire('RATE:IPID:'.$ip.':'.$id, 600);

            Redis::decr('NOT:IP:'.$ip);
            Redis::decr('NOT:ID:'.$id);
            Redis::decr('NOT:IPID:'.$ip.':'.$id);

            if(!$do)
                return Response::json([
                    'success' => false,
                    'challenge_ts' => gmdate('Y-m-d\TH:i:s\Z'),
                    'error_codes' => ['INVALID_CHALLENGE_RESPONSE'] 
                ]);
            if($remoteip && $ip != $remoteip)
                return Response::json([
                    'success' => false,
                    'challenge_ts' => gmdate('Y-m-d\TH:i:s\Z'),
                    'error_codes' => ['INVALID_CHALLENGE_IP'] 
                ]);

            Redis::del('CHALLENGE:RESPONSE:'.$response);
            Redis::del('IP:RESPONSE:'.$response);
            Redis::del('ID:RESPONSE:'.$response);

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
    public function falljs()
    {
        return response()->view('frontend.captcha.falljs')->withHeaders(['Content-Type' => 'application/x-javascript']);
    }
}
