@extends('admin.common.app')

@section('styles')
    <!-- iCheck -->
    <link href="{{ asset('assets/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>上传记录</h5>
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
                            <label class="col-sm-2 control-label">上报人</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">{{ $event->user->name }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">所属单位</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">{{ $event->user->department->name }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">问题类型</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">{{ $event->category }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">问题描述</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">{{ $event->description }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">处理地点</label>

                            <div class="col-sm-10">
                                @if( $event->status == 1)
                                    <p class="form-control-static" style="color: red">处理完成</p>
                                @else
                                    <p class="form-control-static" style="color: red">未处理完成</p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">问题现场图片</label>

                            <div class="col-sm-10">
                                @foreach(json_decode($event->img) as $image)
                                <a class="fancybox" id="img" href="{{ $image }}" >
                                    <img alt="image" src="{{ $image }}" />
                                </a>
                                    @endforeach
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <!-- iCheck -->
    <script src="{{ asset('assets/admin/js/plugins/iCheck/icheck.min.js') }}"></script>

    <!-- Fancy box -->
    <script src="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.js') }}"></script>

@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
            $('.fancybox').fancybox({
                openEffect: 'none',
                closeEffect: 'none'
            });
        });
    </script>
@endsection