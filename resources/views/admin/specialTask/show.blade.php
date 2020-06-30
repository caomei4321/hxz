@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{{ $commonTask->title }}</h5>
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
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>执行人</th>
                            <th>所属单位</th>
                            <th>地点</th>
                            <th>执行时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($commonTasks as $commonTask)
                            <tr class="gradeC">
                               {{-- {{ dd($commonTask) }}--}}
                                <td>{{ $commonTask->name }}</td>
                                <td>{{ $commonTask->department->name }}</td>
                                <td>{{ $commonTask->pivot->address }}</td>
                                <td>{{ $commonTask->pivot->up_at }}</td>
                                <td class="center">
                                    @if($commonTask->pivot->address)
                                    <a href="{{ route('admin.specialProcess.show', ['commonProcess' => $commonTask->pivot->id]) }}"><button type="button" class="btn btn-danger btn-xs" id="show">查看</button></a>
                                    @endif
                                    {{--<button class="btn btn-warning btn-xs delete" data-id="{{ $dailyTask->id }}">删除</button>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>执行人</th>
                            <th>所属单位</th>
                            <th>地点</th>
                            <th>执行时间</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
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

    <script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/layer/layer.js') }}"></script>
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
                    url: '/admin/temporaryTasks/'+id,
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
        $('#test').click(function () {
            layer.open({
                type: 2,
                area: ['700px', '450px'],
                fixed: false, //不固定
                maxmin: true,
                content: "{{ route('admin.dailyTask.create') }}"
            });
        });
    </script>
@endsection