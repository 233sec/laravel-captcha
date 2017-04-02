@extends ('frontend.layouts.app')

@section ('title', trans('menus.backend.vul.vuls.management') . '|' . trans('menus.backend.vul.vuls.list'))

@section('content')
<div class="col-xs-12">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">我的应用</div>
            <div class="panel-body">
                {{ $app_detail->template(['lengend_1' => ['lengend']])->make() }}
            </div>
        </div>
    </div>
</div>
@endsection

