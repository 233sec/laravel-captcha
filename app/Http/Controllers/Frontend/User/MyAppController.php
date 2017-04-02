<?php

namespace App\Http\Controllers\Frontend\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Yajra\Datatables\Facades\Datatables;
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
                ->where(['user_id' => auth()->id()])
            )->make(true);
        }

        return response()->view('frontend.user.myapp');
    }

    public function detail($id)
    {

    }

    public function create()
    {

    }
}
