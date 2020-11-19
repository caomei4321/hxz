<!--左侧导航开始-->
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
{{--                    <span><img alt="image" class="img-circle" src="{{ asset('assets/admin/img/profile_small.jpg') }}" /></span>--}}
                    <span><img alt="image" style="height: 60px; border-radius: 0%;" class="img-circle" src="{{ asset('assets/admin/img/zhwjz.png') }}" /></span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold">admin</strong></span>
                                <span class="text-muted text-xs block">超级管理员<b class="caret"></b></span>
                                </span>
                    </a>
                </div>
                <div class="logo-element">SH
                </div>
            </li>
            <li>
                <a class="J_menuItem" href="{{ url('admin/count') }}" data-index="0"><i class="fa fa-home"></i>首页</a>
            </li>
            @if(auth()->user()->can('admin\user') || auth()->user()->id == 1)
            <li>
                <a class="J_menuItem" href="{{ route('admin.user.index') }}"><i class="fa fa-users"></i> <span class="nav-label">人员信息</span></a>
            </li>
            @endif
            @if(auth()->user()->can('admin\userCategory') || auth()->user()->id == 1)
            <li>
                <a class="J_menuItem" href="{{ route('admin.userCategory.index') }}"><i class="fa fa-users"></i> <span class="nav-label">人员分类</span></a>
            </li>
            @endif
            @if(auth()->user()->can('admin\department') || auth()->user()->id == 1)
            <li>
                <a class="J_menuItem" href="{{ route('admin.department.index') }}"><i class="fa fa-users"></i> <span class="nav-label">部门信息</span></a>
            </li>
            @endif
            @if(auth()->user()->can('admin\message') || auth()->user()->id == 1)
            <li>
                <a class="J_menuItem" href="{{ route('admin.message.index') }}"><i class="fa fa-desktop"></i> <span class="nav-label">通知管理</span></a>
            </li>
            @endif
            @if(auth()->user()->can('admin\sign') || auth()->user()->id == 1)
            <li>
                <a class="J_menuItem" href="{{ route('admin.sign.index') }}"><i class="fa fa-desktop"></i> <span class="nav-label">签到统计</span></a>
            </li>
            @endif
            <li>
                <a class="J_menuItem" href="{{ route('admin.handoverRecord.index') }}"><i class="fa fa-desktop"></i> <span class="nav-label">交接班记录</span></a>
            </li>
            <li>
                <a href="#"><i class="fa fa-table"></i> <span class="nav-label">任务管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @if(auth()->user()->can('admin\dailyTask') || auth()->user()->id == 1)
                    <li><a class="J_menuItem" href="{{ route('admin.dailyTask.index') }}"><i class="fa fa-map-marker"></i> <span class="nav-label">日常任务</span></a></li>
                    @endif
                    @if(auth()->user()->can('admin\dailyProcess') || auth()->user()->id == 1)
                    <li><a class="J_menuItem" href="{{ route('admin.dailyProcess.index') }}"><i class="fa fa-user"></i> <span class="nav-label">日常处理记录</span></a></li>
                    @endif
                    @if(auth()->user()->can('admin\temporaryTask') || auth()->user()->id == 1)
                    <li><a class="J_menuItem" href="{{ route('admin.temporaryTask.index') }}"><i class="fa fa-map-marker"></i> <span class="nav-label">下发任务</span></a></li>
                    @endif
                    @if(auth()->user()->can('admin\temporaryProcess') || auth()->user()->id == 1)
                    <li><a class="J_menuItem" href="{{ route('admin.temporaryProcess.index') }}"><i class="fa fa-user"></i> <span class="nav-label">临时处理记录</span></a></li>
                    @endif
                    @if(auth()->user()->can('admin\specialTask') || auth()->user()->id == 1)
                    <li><a class="J_menuItem" href="{{ route('admin.specialTask.index') }}"><i class="fa fa-map-marker"></i> <span class="nav-label">专项任务</span></a></li>
                    @endif
                    @if(auth()->user()->can('admin\specialProcess') || auth()->user()->id == 1)
                    <li><a class="J_menuItem" href="{{ route('admin.specialProcess.index') }}"><i class="fa fa-user"></i> <span class="nav-label">专项处理记录</span></a></li>
                    @endif
                </ul>
            </li>
            @if(auth()->user()->can('admin\event') || auth()->user()->id == 1)
            <li>
                <a class="J_menuItem" href="{{ route('admin.event.index') }}"><i class="fa fa-calendar-minus-o"></i> <span class="nav-label">上报事件</span></a>
            </li>
            @endif
            @if(auth()->user()->can('admin\manual') || auth()->user()->id == 1)
            <li>
                <a class="J_menuItem" href="{{ route('admin.manual.index') }}"><i class="fa fa-users"></i> <span class="nav-label">手册</span></a>
            </li>
            @endif
            <li>
                <a href="#"><i class="fa fa-table"></i> <span class="nav-label">系统管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @if(auth()->user()->can('admin\administrators') || auth()->user()->id ===1)
                        {{--@can('admin\administrators')--}}
                        <li><a class="J_menuItem" href="{{ route('admin.administrators.index') }}">管理员</a>
                        </li>
                        {{--@endcan--}}
                    @endif
                    @if(auth()->user()->can('admin\roles') || auth()->user()->id ===1)
                        {{--@can('admin\roles')--}}
                        <li><a class="J_menuItem" href="{{ route('admin.roles.index') }}">角色</a>
                        </li>
                        {{--@endcan--}}
                    @endif
                    @if(auth()->user()->can('admin\permissions') || auth()->user()->id ===1)
                        {{--@can('admin\permissions')--}}
                        <li><a class="J_menuItem" href="{{ route('admin.permissions.index') }}">权限</a>
                        </li>
                        {{--@endcan--}}
                    @endif
                </ul>
            </li>

        </ul>
    </div>
</nav>
<!--左侧导航结束-->