@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
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
                    @if(empty($user->id))
                        <form method="POST" id="user_form" action="#" data-action="{{ route('admin.user.store') }}" class="form-horizontal">
                    @else
                        <form method="POST" id="user_form" action="#" data-action="{{ route('admin.user.update',$user->id) }}" class="form-horizontal">
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
                            <label class="col-sm-2 control-label">姓名：</label>

                            <div class="col-sm-6">
                                <input name="name" id="name" type="text" placeholder="" class="form-control" value="{{ old('name', $user->name) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">年龄：</label>

                            <div class="col-sm-6">
                                <input name="age" id="age" type="text" placeholder="" class="form-control" value="{{ old('age', $user->age) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">手机号：</label>

                            <div class="col-sm-6">
                                <input name="phone" id="phone" type="text" placeholder="" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">密码：</label>

                            <div class="col-sm-6">
                                <input name="password" id="password" type="password" placeholder="" class="form-control" value="{{ old('password', $user->password) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">分类：</label>
                            <div class="col-sm-6">
                                <select class="chosen-select" data-placement="选择角色" name="category_id" style="width: 350px;" tabindex="2">
                                    @if($user->id)
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @if($user->category_id == $category->id) selected="selected" @endif>{{ $category->name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary" id="add_user" type="button">提交</button>
                            </div>
                        </div>
                    </form>
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
            /*$(document).ready(function () {
            $("#add_device").onclick(function () {
                var data = {
                    'truck_pass' : $('#truck_name').val(),
                };
                console.log(data);
            })
        });*/
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

        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
        var url = $("#user_form").data('action');

            $('#add_user').on('click', function(){
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: url,
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: $("#user_form").serialize(),
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
