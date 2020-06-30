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
                    <h5>手册信息</h5>
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
                    <a href="{{ route('admin.manual.create') }}"><button class="btn btn-info " id="add_manual" type="button"><i class="fa fa-paste"></i> 添加手册</button>
                    </a>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>排序</th>
                            <th>添加时间</th>
                            <th>上次更新时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($manuals as $manual)
                            <tr class="gradeC">
                                <td>{{ $manual->id }}</td>
                                <td>{{ $manual->title }}</td>
                                <td>{{ $manual->sort }}</td>
                                <td>{{ $manual->created_at }}</td>
                                <td>{{ $manual->updated_at }}</td>
                                <td class="center">
                                    <a href="{{ route('admin.manual.edit', ['manual' => $manual->id]) }}"><button class="btn btn-primary btn-xs" data-id="{{ $manual->id }}">编辑</button></a>
                                    {{--                                    <a href=""><button type="button" class="btn btn-danger btn-xs">查看</button></a>--}}
                                    <button class="btn btn-danger btn-xs edit" data-id="{{ $manual->id }}">修改排序</button>
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $manual->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>排序</th>
                            <th>添加时间</th>
                            <th>上次更新时间</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $manuals->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Data Tables -->
    <script src="{{ asset('assets/admin/js/plugins/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>

    <!-- Sweet alert -->
    {{--<script src="{{ asset('assets/admin/js/plugins/sweetalert/sweetalert.min.js') }}"></script>--}}

    <script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/layer/layer.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/sweet/sweetalert.min.js') }}"></script>
@endsection

@section('javascript')
    <script>
        $('.edit').click(function () {
            var id = $(this).data('id');
            swal({
                text: '修改排序',
                content: 'input',
                button: {
                    text: "修改",
                    closeModal: true
                },
            }).then((sort) => {
                if (!sort) {
                    throw null;
                }
                $.ajaxSetup({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type:"get",
                    url: '/admin/manuals/updateSort/'+id+'?sort='+sort,
                    success:function (res) {
                        if (res.status == 200){
                            swal(res.message, "您已经永久删除了这条信息。", "success");
                            location.reload()
                            //location.reload();
                        }else {
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
            }).then((willdelete) => {
                if (willdelete) {
                    $.ajaxSetup({
                        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type:"delete",
                        url: '/admin/manuals/'+id,
                        success:function (res) {
                            if (res.status == 200){
                                swal(res.message, "您已经永久删除了这条信息。", "success");
                                location.reload()
                                //location.reload();
                            }else {
                                swal(res.message, "请稍后重试。", "warning");
                            }
                        },
                    });
                    $.ajax();
                }

            })
        });


        /*$('#add_category').click(function () {
            layer.open({
                type: 2,
                area: ['700px', '450px'],
                fixed: false, //不固定
                maxmin: true,
                content: "{{ route('admin.userCategory.create') }}"
            });
        });
        $('.edit').click(function () {
            var id = $(this).data('id');
            layer.open({
                type: 2,
                area: ['700px', '450px'],
                fixed: false, //不固定
                maxmin: true,
                content: "userCategories/"+id+"/edit"
            });
        });*/
    </script>
@endsection