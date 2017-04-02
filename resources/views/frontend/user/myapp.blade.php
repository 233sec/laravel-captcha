@extends ('frontend.layouts.app')

@section ('title', trans('labels.backend.access.users.management') . ' | ' . trans('labels.backend.access.users.deleted'))

@section('after-styles')
    {{ Html::style("css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
<style>
.text-monospace {
    font-family: monospace;
}
</style>
@endsection

@section('content')
<div class="col-xs-12">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">我的应用</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="users-table" class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>NAME</th>
                                <th>APPKEY</th>
                                <th>APPSECRET</th>
                                <th>{{ trans('labels.general.actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <a class="btn btn-primary btn-sm no-corner" href="{{ route('frontend.user.my.app.create') }}">添加</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after-scripts')
    {{ Html::script("js/backend/plugin/datatables/jquery.dataTables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables.bootstrap.min.js") }}

	<script>
		$(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("frontend.user.my.app", ['type' => "json"]) }}',
                    type: 'get',
                    data: {status: false, trashed: true}
                },
                columns: [
                    {data: 'name', name: 'app.name', searchable: false, sortable: false, class: 'text-monospace'},
                    {data: 'key', name: 'app.key', searchable: false, sortable: false, class: 'text-monospace'},
                    {data: 'secret', name: 'app.secret', searchable: false, sortable: false, class: 'text-monospace'},
                    {data: function(a){return '';}, name: 'actions', searchable: false, sortable: false, class: 'text-monospace'}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });

		});
	</script>
@endsection
