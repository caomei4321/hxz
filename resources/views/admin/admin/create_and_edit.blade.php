@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    @if($administrator->id)
                        <h5>修改信息</h5>
                    @else
                        <h5>添加管理员</h5>
                    @endif
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
                    @if(empty($administrator->id))
                        <form method="post" action="{{ route('admin.administrators.store') }}" class="form-horizontal">
                            @else
                                <form method="POST" action="{{ route('admin.administrators.update',$administrator->id) }}" class="form-horizontal">
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
                                            <input name="name" id="name" type="text" class="form-control" value="{{ old('name',$administrator->name) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">手机号：</label>

                                        <div class="col-sm-6">
                                            <input name="phone" id="phone" type="phone" class="form-control" value="{{ old('phone',$administrator->phone) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">密码：</label>

                                        <div class="col-sm-6">
                                            <input name="password" id="password" type="password" class="form-control" value="{{ old('password',$administrator->password) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">部门：</label>

                                        <div class="col-sm-6">
                                            <select class="chosen-select" name="department_id" style="width: 200px;" tabindex="2" >
                                                <option value="">选择部门</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" hassubinfo="true" @if( old('department_id', $administrator->department_id) == $department->id) selected @endif>{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">用户角色：</label>
                                        <div class="col-sm-6">
                                            {{--multiple--}}
                                            <select class="chosen-select" data-placement="选择角色" name="administrator_roles[]" multiple style="width: 350px;" tabindex="2">
                                                @if($administrator->id)
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" @if (in_array($role->name,$administrator_roles)) selected="selected" @endif>{{ $role->name }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary" id="add_device">提交</button>
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
    </script>
@endsection
