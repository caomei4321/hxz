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
                    <h5>预约记录</h5>
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
                    {{--<button class="btn btn-info " id="add_department" type="button"><i class="fa fa-paste"></i> 添加岗亭</button>--}}
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>岗亭</th>
                            <th>预约人</th>
                            <th>预约时间</th>
                            <th>状态</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($appointmentRecords as $appointmentRecord)
                            <tr class="gradeC">
                                <td>{{ $appointmentRecord->id }}</td>
                                <td>{{ $appointmentRecord->station->name }}</td>
                                <td>{{ $appointmentRecord->user->name }}</td>
                                <td>{{ $appointmentRecord->appointment_time }}</td>
                                @if($appointmentRecord->status == 0)
                                    <td>未处理</td>
                                @elseif($appointmentRecord->status == 1)
                                    <td>已同意预约</td>
                                @else
                                    <td>已拒绝预约</td>
                                @endif
                                <td>{{ $appointmentRecord->created_at }}</td>
                                <td class="center">
                                    <button class="btn btn-primary btn-xs edit" data-id="{{ $appointmentRecord->id }}">处理预约</button>
{{--                                    <a href=""><button type="button" class="btn btn-danger btn-xs">查看</button></a>--}}
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $appointmentRecord->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>岗亭</th>
                            <th>预约人</th>
                            <th>预约时间</th>
                            <th>状态</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $appointmentRecords->links() }}
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
    <script src="{{ asset('assets/admin/js/plugins/sweet/sweetalert.min.js') }}"></script>

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
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then((willdelete) => {
                if (willdelete) {
                    $.ajaxSetup({
                        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type:"delete",
                        url: '/admin/appointmentRecords/'+id,
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

        $('.edit').click(function () {
            var id = $(this).data('id');
            swal("同意或拒绝此次预约", {
                buttons: {
                    cancel: "取消",
                    agree: "同意",
                    reject: "拒绝",
                },
            }).then((value) => {
                switch (value) {
                    case "agree":
                        handleAppointment(id, 1);
                        break;
                    case "reject":
                        handleAppointment(id, 2);
                        break;
                    default:
                        swal.close();
                    }
            });
        });
        function handleAppointment(id, status) {
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"get",
                url: '/admin/appointmentRecords/changeStatus/'+ id + '?status=' + status,
                success:function (res) {
                    if (res.status == 200){
                        swal(res.message, "您已成功修改任务状态。", "success");
                        location.reload()
                        //location.reload();
                    }else {
                        swal(res.message, "请稍后重试。", "warning");
                    }
                },
            });
            $.ajax();
        }
    </script>
@endsection