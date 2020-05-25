@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <!-- Data picker -->
    <link href="{{ asset('assets/admin/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
@endsection
<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>

@section('content')

    <div class="row">
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>总人数</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $count['userCount'] }}</h1>
                    <div class="stat-percent font-bold text-success"> <i class="fa fa-bolt"></i>
                    </div>
                    <small>&nbsp</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">当日</span>
                    <h5>上报数量</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $count['uploadCount'] }}</h1>
                    <div class="stat-percent font-bold text-info"> <i class="fa fa-bolt"></i>
                    </div>
                    <small>&nbsp</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">当日</span>
                    <h5>处理任务数量</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $count['taskProcessCount'] }}</h1>
                    <div class="stat-percent font-bold text-navy"> <i class="fa fa-bolt"></i>
                    </div>
                    <small>&nbsp</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">当日</span>
                    <h5>日常提交数量</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $count['uploadEventCount'] }}</h1>
                    <div class="stat-percent font-bold text-danger"><i class="fa fa-bolt"></i>
                    </div>
                    <small>&nbsp</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>最新日常任务上报</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="index.html#">选项1</a>
                            </li>
                            <li><a href="index.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content no-padding">
                    <ul class="list-group">
                        @foreach($dailyTasks as $dailyTask)
                            <li class="list-group-item">
                                <p><a class="text-info" href="#">#{{ $dailyTask->user->name }}#</a> {{ $dailyTask->description }}</p>
                                <small class="block text-muted"><i class="fa fa-clock-o"></i> {{ $dailyTask->created_at }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>最新临时和专项任务上报</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="index.html#">选项1</a>
                            </li>
                            <li><a href="index.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content no-padding">
                    <ul class="list-group">
                        @foreach($commonTasks as $commonTask)
                            <li class="list-group-item">
                                <p> {{ $commonTask->description }}</p>
                                <small class="block text-muted"><i class="fa fa-clock-o"></i> {{ $commonTask->created_at }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>最新发现事件上报</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="index.html#">选项1</a>
                            </li>
                            <li><a href="index.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content no-padding">
                    <ul class="list-group">
                        @foreach($uploadEvents as $uploadEvent)
                            <li class="list-group-item">
                                <p><a class="text-info" href="#">#{{ $uploadEvent->user->name }}#</a> {{ $uploadEvent->content }}</p>
                                <small class="block text-muted"><i class="fa fa-clock-o"></i> {{ $uploadEvent->created_at }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/echarts.js') }}"></script>
    <!-- Data picker -->
    <script src="{{ asset('assets/admin/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <!-- Flot -->
    <script src="{{ asset('assets/admin/js/plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/flot/jquery.flot.spline.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/flot/jquery.flot.symbol.js') }}"></script>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('#datepicker').datepicker();
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
        })
    </script>
@endsection