@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>人员详情</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="form_basic.html#">选项1</a>
                                </li>
                                <li><a href="form_basic.html#">选项2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form method="get" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">姓名</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">联系电话</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->phone }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">类别</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->category->name }}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </form>
                    </div>
                    <div class="ibox-content">
                        <h3>执行任务记录</h3>
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>任务ID</th>
                                <th>标题</th>
                                <th>地点</th>
                                <th>来源</th>
                                <th>完成情况</th>
                                <th>开始时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($matters as  $matter)
                            <tr class="gradeX">
                                <td>{{ $matter->id }}</td>
                                <td>{{ $matter->title }}</td>
                                <td class="center">{{ $matter->address }}</td>
                                @if($matter->form == 1)
                                    <td class="center">任务导入</td>
                                @elseif($matter->form == 3)
                                    <td class="center">群众上报</td>
                                @endif

                                @if($matter->status == 1 )
                                    <td class="center">已查看</td>
                                @else
                                    <td class="center">未查看</td>
                                @endif
                                <td class="center">{{ $matter->created_at }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>任务ID</th>
                                <th>标题</th>
                                <th>地点</th>
                                <th>来源</th>
                                <th>完成情况</th>
                                <th>开始时间</th>
                            </tr>
                            </tfoot>
                        </table>
                        {{ $matters->links() }}
                    </div>
                    <div class="ibox-content">
                        <h3>发现问题记录</h3>
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>问题ID</th>
                                <th>标题</th>
                                <th>描述</th>
                                <th>处理情况</th>
                                <th>开始时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($patrolMatters as  $patrolMater)
                                <tr class="gradeX">
                                    <td>{{ $patrolMater->id }}</td>
                                    <td>{{ $patrolMater->title }}</td>
                                    <td class="center">{{ $patrolMater->content }}</td>
                                    @if($patrolMater->status == 1)
                                        <td class="center">处理完成</td>
                                    @elseif($patrolMater->status == 0)
                                        <td class="center">未处理完成</td>
                                    @endif
                                    <td class="center">{{ $matter->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>问题ID</th>
                                <th>标题</th>
                                <th>描述</th>
                                <th>处理情况</th>
                                <th>开始时间</th>
                            </tr>
                            </tfoot>
                        </table>
                        {{ $patrolMatters->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Chosen -->
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>
@endsection
<!-- 自定义js -->
{{--<script src="{{ asset('assets/admin/js/content.js?v=1.0.0') }}"></script>--}}
@section('javascript')
    <script>

    </script>
@endsection
