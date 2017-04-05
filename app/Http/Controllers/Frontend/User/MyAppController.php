<?php

namespace App\Http\Controllers\Frontend\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Yajra\Datatables\Facades\Datatables;
use Tsssec\Editable\Editable;
use Response;
use DB;

class MyAppController extends Controller
{
    public function index(Request $request)
    {
        if($request->query('type') == 'json')
        {
            return Datatables::queryBuilder(
                DB::table('user_app')
                ->join('app', 'app.id', '=', 'user_app.app_id')
                ->select(['user_app.*', 'app.name', 'app.key', 'app.secret', 'app.theme', 'app.domain'])
                ->where(['user_app.user_id' => auth()->id()])
                ->orderBy('user_app.id', 'asc')
            )->make(true);
        }

        return response()->view('frontend.user.myapp');
    }

    public function detail(Request $request, $key)
    {
        $detail = new Editable();
        return $detail->options([
            'instance' => 'app',
            'primary_key' => 'key',
            'i18n' => [
                'lengend_1'     => '应用详情',
                'name'          => '应用名称',
                'key'           => 'APPKEY',
                'secret'        => 'APPSECRET',
                'domain'        => '域名',
                'theme'         => '样式',
                'created_at'    => '创建时间',
                'updated_at'    => '修改时间',
            ],
            'edit' => true
        ])
        ->queryBuilder(
            DB::table('app')
            ->join('user_app', 'user_app.app_id', '=', 'app.id', 'inner')
            ->where([ 'app.key' => $key ])
            ->where(['user_app.user_id' => auth()->id()])
            ->select(['app.*'])
        )->onsubmit(function($update) use($key){
            Redis::set('APP:KEY:'.$key, json_encode($update));
            unset($update['secret']);
            unset($update['created_at']);
            unset($update['updated_at']);
            return $update;
        })->ready(function($detail) use($key){
            return view('frontend.user.myapp_detail', ['detail' => $detail, 'key' => $key]);
        });
    }

    public function create()
    {

        try{
            $app_detail = new Editable();
            $app_detail->options([
                'instance' => 'app',
                'primary_key' => 'id',
                'i18n' => [
                    'lengend_1'     => '应用详情',
                    'id'            => 'ID',
                    'name'          => '标题',
                ],
                'edit' => true
            ])
            ->insertTo(DB::table('app'))
            ->onsubmit(
                function($data){
                    unset($data['id']);

                    $data['active']     = 1;
                    $data['theme']      = 'default';
                    $data['domain']     = 'yourdomain.com';
                    $data['key']        = $this->str_rand(20);
                    $data['secret']     = $this->str_rand(40);
                    $data['created_at'] = date('Y/m/d H:i:s');
                    $data['updated_at'] = null;

                    $appid = DB::table('app')->insertGetId($data);
                    DB::table('user_app')->insert([
                        'app_id'  => $appid,
                        'user_id' => auth()->user()->getAuthIdentifier()
                    ]);
                    throw new \Exception('OK', 0);
                }
            )
            ->ready();
        }catch(\Exception $e){
            return \Response::json([
                'head' => ['statusCode' => $e->getCode(), 'note' => $e->getMessage()],
                'body' => []
            ]);
        }

        return view('frontend.user.createapp', ['app_detail' => $app_detail, 'app_id' => 0]);
    }

    protected function str_rand($length)
    {
        $string = '';
        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= str_shuffle(str_shuffle(substr(strrev(str_replace('=', '_', str_replace('+', '.', str_replace('/', '-', base64_encode($bytes))))), 0, $size)));
        }
        return $string;
    }
}
