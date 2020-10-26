@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/style.css?v=4.1.0') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>临时任务</h5>
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

                            <a href="{{ route('admin.temporaryTask.create') }}">
                                <button class="btn btn-info " id="add_task" type="button"><i class="fa fa-paste"></i>
                                    发布任务
                                </button>
                            </a>
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" CLASS="input-sm form-control" name="start_time"
                                       value="{{ isset($filter['start_time']) ? $filter['start_time'] : date("Y-m-d",time()) }}"/>
                                <span class="input-group-addon">到</span>
                                <input type="text" class="input-sm form-control" name="end_time"
                                       value="{{ isset($filter['end_time']) ? $filter['end_time'] : date("Y-m-d",time()) }}"/>
                            </div>

                            <div class="form-group">
                                <button onclick="submitForm('search')" class="btn btn-primary">搜索</button>
                                <button onclick="submitForm('export')" class="btn btn-primary">导出报表</button>
                            </div>

                        </div>

                    </form>


                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>任务说明</th>
                            <th>任务分类</th>
                            <th>小区名称</th>
                            <th>任务状态</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($commonTasks as $commonTask)
                            <tr class="gradeC">
                                <td>{{ $commonTask->id }}</td>
                                <td>{{ $commonTask->title }}</td>
                                <td>{{ $commonTask->content }}</td>
                                <td>{{ $commonTask->category }}</td>
                                <td>
                                    @foreach($commonTask->users->groupBy('department_id') as $users)
                                        @for($i=0; $i<1; $i++)
                                            {{ $users[0]->department->name.'；' }}
                                        @endfor
                                    @endforeach
                                </td>
                                @if($commonTask->status == 1)
                                    <td>进行中</td>
                                @elseif($commonTask->status == 0)
                                    <td>已完结</td>
                                @endif
                                <td>{{ $commonTask->created_at }}</td>
                                <td class="center">
                                    <a href="{{ route('admin.temporaryTask.show', ['commonTask' => $commonTask->id]) }}">
                                        <button type="button" class="btn btn-danger btn-xs" id="show"
                                                data-id="{{ $commonTask->id }}">查看
                                        </button>
                                    </a>
                                    <button class="btn btn-info btn-xs edit" data-id="{{ $commonTask->id }}">修改状态
                                    </button>
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $commonTask->id }}">删除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>任务说明</th>
                            <th>任务分类</th>
                            <th>小区名称</th>
                            <th>任务状态</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $commonTasks->appends($filter)->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Data Tables -->
    <script src="{{ asset('assets/admin/js/plugins/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>

    <!-- Sweet alert -->
    <script src="{{ asset('assets/admin/js/plugins/sweet/sweetalert.min.js') }}"></script>
    <!-- Data picker -->
    <script src="{{ asset('assets/admin/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <!-- Chosen -->
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>

    <script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/layer/layer.js') }}"></script>
@endsection

@section('javascript')
    <script>
        $('.edit').click(function () {
            var id = $(this).data('id');
            swal({
                title: "您确定要修改这条任务状态吗",
                text: "修改后执行人员不会显示此条任务",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then((change) => {
                if (!change) {
                    throw null;
                }
                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "get",
                    url: '/admin/temporaryTask/changeStatus/' + id,
                    success: function (res) {
                        if (res.status == 200) {
                            swal(res.message, "您已成功修改任务状态。", "success");
                            location.reload()
                            //location.reload();
                        } else {
                            swal(res.message, "请稍后重试。", "warning");
                        }
                    },
                });
                $.ajax();
            })
        });
        $('.delete').click(function () {
            var id = $(this).data('id');
            swal({
                title: "您确定要删除这条信息吗",
                text: "删除后将无法恢复，请谨慎操作！",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: "delete",
                        url: '/admin/temporaryTasks/' + id,
                        success: function (res) {
                            if (res.status == 200) {
                                swal(res.message, "您已经永久删除了这条信息。", "success");
                                location.reload();
                            } else {
                                swal(res.message, "请稍后重试。", "waring");
                            }
                        },
                    });
                    $.ajax();
                }
            })
        });

        $('#datepicker').datepicker();
        $('.dataTables-example').dataTable({
            "lengthChange": false,
            "paging": false
        });


        function submitForm(type) {
            var obj = $('#form');
            if (type == 'export') {
                obj.attr('action', "{{ route('admin.temporaryTask.export') }}");
                obj.submit();
            } else if (type == 'search') {
                obj.attr('action', "{{ route('admin.temporaryTask.index') }}");
                obj.submit();
            }
        }



    </script>
@endsection