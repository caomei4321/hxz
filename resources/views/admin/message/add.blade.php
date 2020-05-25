@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
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
                        <form method="post" action="#" id="message_form" data-action="{{ route('admin.message.store') }}" class="form-horizontal">
                                    <div class="form-group">
                                        @if( count($errors) >0)
                                            @foreach($errors->all() as $error)
                                                <p class="text-danger text-center">{{ $error }}</p>
                                            @endforeach
                                        @endif
                                    </div>
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">标题：</label>

                                        <div class="col-sm-6">
                                            <input name="title" id="title" type="text" placeholder="" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">通知内容：</label>

                                        <div class="col-sm-6">
                                            <textarea id="content" name="content" class="form-control" required="" aria-required="true"></textarea>
                                        </div>
                                        {{--<div class="col-sm-6">
                                            <input name="content" id="content" type="text" placeholder="" class="form-control" value="">
                                        </div>--}}
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">通知人 ：</label>
                                        @foreach($userCategories as $userCategory)
                                            @if(count($userCategory->users) == 0)
                                                <?php continue; ?>
                                            @endif
                                            <div class="col-sm-6">
                                                <p>{{ $userCategory->name }}</p>
                                                @foreach($userCategory->users as $user)
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" name="users[]" id="inlineCheckbox{{$user->id}}" value="{{ $user->id }}">
                                                        <label for="inlineCheckbox{{$user->id}}"> {{ $user->name }} </label>
                                                    </div>
                                                @endforeach

                                            </div>
                                        @endforeach
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary" type="button" id="add_message">提交</button>

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

@section('javascript')
     <script>
         var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
         var url = $("#message_form").data('action');

         $('#add_message').on('click', function(){
             $.ajax({
                 type: "post",
                 dataType: "json",
                 url: url,
                 headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                 data: $("#message_form").serialize(),
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
