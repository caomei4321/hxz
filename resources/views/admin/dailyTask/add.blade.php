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
                    <form method="get" action="{{ route('admin.dailyTask.create') }}">
                        <div class="form-group form-inline row text-left" id="data_5">
                            <div class="form-group">
                                <select class="chosen-select" name="department_id" style="width: 200px;" tabindex="2" >
                                    <option value="">选择部门</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" hassubinfo="true" @if( $filter['department_id'] == $department->id) selected @endif>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="chosen-select" name="category_id" style="width: 200px;" tabindex="2" >
                                    <option value="">选择分类</option>
                                    @foreach($userCategories as $category)
                                        <option value="{{ $category->id }}" hassubinfo="true" @if( $filter['category_id'] == $category->id) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <input type="submit" class="btn btn-primary" value="搜索人员">
                            </div>

                        </div>
                    </form>
                        <form method="post" action="{{ route('admin.dailyTask.store') }}" id="daily_form" data-action="{{ route('admin.dailyTask.store') }}" class="form-horizontal">

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
                                        <label class="col-sm-2 control-label">任务说明：</label>

                                        <div class="col-sm-6">
                                            <textarea id="content" name="content" class="form-control" required="" aria-required="true"></textarea>
                                        </div>
                                    </div>
                                    {{--<div class="form-group">
                                        <label class="col-sm-2 control-label">任务状态：</label>

                                        <div class="col-sm-6">
                                            <input name="status" id="status" type="text" placeholder="" class="form-control" value="">
                                        </div>
                                    </div>--}}
                            @foreach($userCategories as $k => $userCategory)
                                    <div class="form-group">
                                        @if($k == 0)
                                            <label class="col-sm-2 control-label">执行人：</label>
                                        @else
                                            <label class="col-sm-2 control-label"> </label>
                                        @endif

                                            @if(count($userCategory->users) == 0)
                                                <?php continue; ?>
                                            @endif
                                            @if($filter['category_id'] && $filter['category_id'] != $userCategory->id)
                                                <?php continue; ?>
                                            @endif
                                            <div class="col-sm-6">
                                                {{--<p>{{ $userCategory->name }}</p>--}}
                                                <div class="row">
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" data-categoryId="{{$userCategory['id']}}" id="inlineCheckboxCategory{{$userCategory->id}}" onclick="selectAll(this)">
                                                        <label for="inlineCheckboxCategory{{$userCategory->id}}"> {{ $userCategory->name }} </label>
                                                    </div>
                                                </div>
                                                @foreach($userCategory->users as $user)
                                                    @if($filter['department_id'] && $user->department_id != $filter['department_id'] )
                                                        <?php continue; ?>
                                                    @endif
                                                    <div class="checkbox checkbox-inline">
                                                        <input type="checkbox" data-categoryId="{{$userCategory->id}}" name="users[]" id="inlineCheckboxUser{{$user->id}}" value="{{ $user->id }}">
                                                        <label for="inlineCheckboxUser{{$user->id}}"> {{ $user->name }} </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                    </div>
                            @endforeach
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary" type="submit" id="add_daily">提交</button>

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

@section('javascript')
     <script>
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
         for (var selector in config) {
             $(selector).chosen(config[selector]);
         }
         /*$('.dataTables-example').dataTable({
             "lengthChange": false,
             "paging": false
         });*/

         /*
         *  控制全选
         * */
         function selectAll(choiceBtn) {
             var tag = document.getElementsByTagName("input");
             var categoryId = choiceBtn.getAttribute("data-categoryId");
             for (var i = 0; i<tag.length; i++) {
                 var obj = tag[i];
                 if (obj.type == 'checkbox') {
                      var userCategoryId = obj.getAttribute("data-categoryId");
                      if (categoryId == userCategoryId) {
                          console.log(categoryId);
                          obj.checked = choiceBtn.checked;
                      }
                 }
             }
         }
    </script>
@endsection
