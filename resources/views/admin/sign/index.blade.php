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
                    <h5>签到记录</h5>
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
                            <th>ID</th>
                            <th>姓名</th>
                            <th>小区</th>
                            <th>签到时间</th>
                            <th>签到类型</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($signs as $sign)
                            <tr class="gradeC">
                                <td>{{ $sign->id }}</td>
                                <td>{{ $sign->user->name }}</td>
                                <td>{{ $sign->user->department->name }}</td>
                                <td>{{ $sign->created_at }}</td>
                                <td>{{ $sign->type ? '到岗签到' : '离岗签到' }}</td>
                                {{--<td class="center">
                                    <a href="{{ route('admin.temporaryTask.show', ['commonTask' => $commonTask->id]) }}"><button type="button" class="btn btn-danger btn-xs" id="show" data-id="{{ $commonTask->id }}">查看</button></a>
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $commonTask->id }}">删除</button>
                                </td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>姓名</th>
                            <th>小区</th>
                            <th>签到时间</th>
                            <th>签到类型</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $signs->links('vendor.pagination.default') }}
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
    </script>
@endsection