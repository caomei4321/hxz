<!--左侧导航开始-->
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
{{--                    <span><img alt="image" class="img-circle" src="{{ asset('assets/admin/img/profile_small.jpg') }}" /></span>--}}
                    <span><img alt="image" style="height: 60px;" class="img-circle" src="{{ asset('assets/admin/img/zhwjz.png') }}" /></span>
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
            <li>
                <a class="J_menuItem" href="{{ route('admin.user.index') }}"><i class="fa fa-users"></i> <span class="nav-label">人员信息</span></a>
            </li>
            <li>
                <a class="J_menuItem" href="{{ route('admin.userCategory.index') }}"><i class="fa fa-users"></i> <span class="nav-label">人员分类</span></a>
            </li>
            <li>
                <a class="J_menuItem" href="{{ route('admin.message.index') }}"><i class="fa fa-desktop"></i> <span class="nav-label">通知管理</span></a>
            </li>
            <li>
                <a href="#"><i class="fa fa-table"></i> <span class="nav-label">任务管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a class="J_menuItem" href="{{ route('admin.dailyTask.index') }}"><i class="fa fa-user"></i> <span class="nav-label">日常任务</span></a></li>
                    <li><a class="J_menuItem" href="{{ route('admin.commonTask.index') }}"><i class="fa fa-map-marker"></i> <span class="nav-label">其他任务</span></a></li>
                </ul>
            </li>
            <li>
                <a class="J_menuItem" href="{{ route('admin.event.index') }}"><i class="fa fa-calendar-minus-o"></i> <span class="nav-label">上报事件</span></a>
            </li>
            <li>
                <a class="J_menuItem" href=""><i class="fa fa-users"></i> <span class="nav-label">手册</span></a>
            </li>

        </ul>
    </div>
</nav>
<!--左侧导航结束-->