@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/summernote/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/plugins/summernote/summernote-bs3.css') }}" rel="stylesheet">
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
                <div class="ibox-content no-padding">
                    @if(empty($manual->id))
                        <form method="POST" id="manual_form" action="{{ route('admin.manual.store') }}" data-action="{{ route('admin.manual.store') }}" class="form-horizontal">
                            @else
                                <form method="POST" id="manual_form" action="{{ route('admin.manual.update',$manual->id) }}" data-action="{{ route('admin.manual.update',$manual->id) }}" class="form-horizontal">
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
                                        <label class="col-sm-2 control-label">标题：</label>

                                        <div class="col-sm-6">
                                            <input name="title" id="title" type="text" placeholder="" class="form-control" value="{{ old('title', $manual->title) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">排序：</label>

                                        <div class="col-sm-6">
                                            <input name="sort" id="sort" type="text" placeholder="" class="form-control" value="{{ old('sort', $manual->sort) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">内容：</label>

                                        <div class="col-sm-10" id="editor">

                                        </div>
                                        <textarea name="detail" id="detail" style="width:100%; height:200px; display: none;">{{ old('detail', $manual->detail) }}</textarea>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary" id="add_user" type="submit">提交</button>
                                        </div>
                                    </div>
                                </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/plugins/wangEditor/wangEditor.min.js') }}"></script>
@endsection
<!-- 自定义js -->
{{--<script src="{{ asset('assets/admin/js/content.js?v=1.0.0') }}"></script>--}}
@section('javascript')
    <script>
        //$(document).ready(function () {

            /*$('.summernote').summernote({
                lang: 'zh-CN'
            });
*/
        /*$(document).ready(function () {
            var $editor = document.getElementById('editor');

            $editor.html();
        });*/

        var E = window.wangEditor;
        var editor = new E('#editor');


        //editor.cmd.do('insertHTML', "");
        // 自定义菜单配置
        editor.customConfig.menus = [
            'head',
            'bold',
            'italic',
            'underline',
            'foreColor',
            'backColor',
            'justify',
            'image',
        ];
        //editor.customConfig.uploadImgShowBase64 = true;
        editor.customConfig.uploadImgParamsWithUrl = true;
        editor.customConfig.uploadFileName = 'img';
        editor.customConfig.uploadImgServer = '/admin/saveManualImg';


        var $detail = $('#detail');
        editor.customConfig.onchange = function (html) {
            // 监控变化，同步更新到 textarea
            $detail.val(html)
        };
        editor.create();
        editor.txt.html('{!! old('detail', $manual->detail) !!}');
        //});
        /*var edit = function () {
            $("#eg").addClass("no-padding");
            $('.click2edit').summernote({
                lang: 'zh-CN',
                focus: true
            });
        };
        var save = function () {
            $("#eg").removeClass("no-padding");
            var aHTML = $('.click2edit').code(); //save HTML If you need(aHTML: array).
            $('.click2edit').destroy();
        };*/
    </script>
@endsection
