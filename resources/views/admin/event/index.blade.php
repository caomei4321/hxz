@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>上报事件</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="table_data_tables.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="table_data_tables.html#">选项1</a>
                            </li>
                            <li><a href="table_data_tables.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="get" action="" id="form">
                        <div class="form-group form-inline row text-left" id="data_5">
                            {{--<label class="font-noraml">范围选择</label>--}}
                            {{--{{ csrf_field() }}--}}
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="start_time" value="{{ isset($filter['start_time']) ? $filter['start_time'] : date("Y-m-d",time()) }}" />
                                <span class="input-group-addon">到</span>
                                <input type="text" class="input-sm form-control" name="end_time" value="{{ isset($filter['end_time']) ? $filter['end_time'] : date("Y-m-d",time()) }}" />
                            </div>
                            <div class="form-group">
                                <select class="chosen-select" name="department_id" style="width: 200px;" tabindex="2" >
                                    <option value="">选择部门</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" hassubinfo="true" @if( $filter['department_id'] == $department->id) selected @endif>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <button onclick="submitForm('search')" class="btn btn-primary">搜索</button>
                                <button onclick="submitForm('export')" class="btn btn-primary">导出报表</button>
                            </div>

                        </div>
                    </form>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>小区</th>
                            <th>上报人</th>
                            <th>事件描述</th>
                            <th>状态</th>
                            <th>最新回复</th>
                            <th>上报时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($events as $event)
                        <tr class="gradeC">
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->user->department->name }}</td>
                            <td>{{ $event->user->name }}</td>
                            <td>{{ $event->description }}</td>
                            <td>{{ $event->status ? '处理完成':'未处理完成'}}</td>
                            <td>{{ isset($event->replies[0]->reply) ? $event->replies[0]->reply : '' }}</td>
                            <td>{{ $event->created_at }}</td>
                            <td class="center">
                                <a href="{{ route('admin.event.show', ['event' => $event->id]) }}"><button type="button" class="btn btn-danger btn-xs">查看</button></a>
                                <button class="btn btn-warning btn-xs delete" data-id="{{ $event->id }}">删除</button>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>小区</th>
                            <th>上报人</th>
                            <th>事件描述</th>
                            <th>状态</th>
                            <th>最新回复</th>
                            <th>上报时间</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $events->appends($filter)->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Data Tables -->
    <script src="{{ asset('assets/admin/js/plugins/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>

    <!-- Sweet alert -->
    <script src="{{ asset('assets/admin/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <!-- Data picker -->
    <script src="{{ asset('assets/admin/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <!-- Chosen -->
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>

@endsection

@section('javascript')
    <script>
        $('.delete').click(function () {
            var id = $(this).data('id');
            swal({
                title: "您确定要删除这条信息吗",
                text: "删除后将无法恢复，请谨慎操作！",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "删除",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajaxSetup({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type:"delete",
                    url: '/admin/events/'+id,
                    success:function (res) {
                        if (res.status == 200){
                            swal(res.message, "您已经永久删除了这条信息。", "success");
                            location.reload();
                        }else {
                            swal(res.message, "请稍后重试。", "waring");
                        }
                    },
                });
                $.ajax();
            });
        });
        $('#datepicker').datepicker();
        $('#dc').datepicker();
        var config = {
            '.chosen-select': {},
            '.chosen-select-deselect': {
                allow_single_deselect: true
            },
            '.chosen-select-no-single': {
                disable_search_threshold: 10
            },
            '.chosen-select-no-results': {
                no_results_text: 'Oops, nothing found!'
            },
            '.chosen-select-width': {
                width: "95%"
            }
        };
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
        $('.dataTables-example').dataTable({
            "lengthChange": false,
            "paging": false
        });
        function submitForm(type) {
            var obj = $('#form');
            if (type == 'export') {
                obj.attr('action', "{{ route('admin.event.export') }}");
                obj.submit();
            } else if (type == 'search') {
                obj.attr('action', "{{ route('admin.event.index') }}");
                obj.submit();
            }
        }
    </script>
@endsection