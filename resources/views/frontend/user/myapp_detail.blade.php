@extends ('frontend.layouts.app')

@section ('title', '')

@section('content')
<div class="col-xs-12">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">我的应用</div>
            <div class="panel-body">
            {{ $detail
                ->template([
                        'created_at'=>['datetime'],
                        'theme' => ['select', ['default' => '默认', 'light' => '扁平', 'transparent' => '透明', 'dark' => '黑暗']],
                        'active' => ['select', ['1' => '启用', '0' => '不启用']],
                        'lengend_1' => ['lengend'],
                    ])
                ->make() }}
        </div>
    </div>


@endsection

@section('after-scripts-end')
    <script>

    </script>
@endsection
