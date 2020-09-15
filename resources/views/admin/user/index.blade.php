@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>人员信息</h5>
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
                    <form action="{{ route('admin.user.index') }}" method="GET">
                        <div class="col-sm-2" style="display: inline-block">
                            <input name="name" id="name" type="text" placeholder="姓名" class="form-control" value="{{ old('name', $filter['name']) }}">
                        </div>
                        <div class="col-sm-2" style="display: inline-block">
                            <input name="phone" id="phone" type="text" placeholder="手机号" class="form-control" value="{{ old('phone', $filter['phone']) }}">
                        </div>
                        <div class="col-sm-2" style="display: inline-block">
                            <select class="chosen-select" name="department_id" style="width: 200px;" tabindex="2" >
                                <option value="">选择部门</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" hassubinfo="true" @if( $filter['department_id'] == $department->id) selected @endif>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-info" type="submit" style="display: inline-block"><i class="fa fa-paste"></i>查找</button>
                        <button class="btn btn-info " id="add_user" type="button"><i class="fa fa-paste"></i> 添加人员</button>
                    </form>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>姓名</th>
                            <th>手机号</th>
                            <th>部门</th>
                            <th>角色</th>
                            <th>积分</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr class="gradeC">
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->department->name }}</td>
                                <td>{{ $user->category->name }}</td>
                                <td>{{ $user->integral }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td class="center">
                                    <a href="#"><button type="button" class="btn btn-primary btn-xs edit" data-id="{{ $user->id }}">编辑</button></a>
                                    {{--<a href=""><button type="button" class="btn btn-danger btn-xs">查看</button></a>--}}
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $user->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>姓名</th>
                            <th>手机号</th>
                            <th>部门</th>
                            <th>角色</th>
                            <th>积分</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $users->links() }}
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
    <script src="{{ asset('assets/layer/layer.js') }}"></script>
    <!-- Chosen -->
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>
@endsection

@section('javascript')
    <script>
        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
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
        }
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
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
                    url: '/admin/users/'+id,
                    success:function (res) {

                        console.log(res);
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
        $('#add_user').click(function () {
            layer.open({
                type: 2,
                area: ['700px', '450px'],
                fixed: false, //不固定
                maxmin: true,
                content: "{{ route('admin.user.create') }}"
            });
        });

        $('.edit').click(function () {
            var id = $(this).data('id');
            layer.open({
                type: 2,
                area: ['700px', '450px'],
                fixed: false, //不固定
                maxmin: true,
                content: "users/"+id+"/edit"
            });
        });
    </script>
@endsection