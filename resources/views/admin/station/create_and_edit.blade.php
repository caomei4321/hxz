@extends('admin.common.app')

@section('styles')

@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
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
                    @if(empty($station->id))
                        <form method="POST" id="department_form" action="#" data-action="{{ route('admin.station.store') }}" class="form-horizontal">
                    @else
                        <form method="POST" id="department_form" action="#" data-action="{{ route('admin.station.update',$station->id) }}" class="form-horizontal">
                            <input type="hidden" name="_method" value="PUT">
                    @endif
                        <div class="form-group">
                            @if( count($errors) >0)
                                @foreach($errors->all() as $error)
                                    <p class="text-danger text-center">{{ $error }}</p>
                                @endforeach
                            @endif
                        </div>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">名称：</label>

                            <div class="col-sm-6">
                                <input name="name" id="name" type="text" placeholder="" class="form-control" value="{{ old('name',$station->name) }}">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary" id="add_department" type="button">添加</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
<!-- 自定义js -->
{{--<script src="{{ asset('assets/admin/js/content.js?v=1.0.0') }}"></script>--}}
{{--    <script src="{{ asset('assets/admin/js/jquery.form.js') }}"></script>--}}
@section('javascript')
    <script>
        /*$(document).ready(function () {
            $("#add_device").onclick(function () {
                var data = {
                    'truck_pass' : $('#truck_name').val(),
                };
                console.log(data);
            })
        });*/
        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引

        var url = $("#department_form").data('action');

        $('#add_department').on('click', function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: url,
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: $("#department_form").serialize(),
                success: function (res) {
                    if (res.status == 200) {
                        parent.window.location.reload();
                    } else {
                        parent.layer.close(index);
                    }
                },
                error: function (res) {
                    
                }
            });
            //parent.window.location.reload();
            //parent.layer.close(index);
        });

    </script>
@endsection
