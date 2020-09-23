<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::redirect('/', 'admin/login' );
Route::get('/', function (){
    return redirect()->route('admin.index');
});

Auth::routes();


//Route::post('admin/saveManualImg', 'Admin\ManualsController@saveImg');

Route::group(['prefix' => 'admin'], function () {

    Route::get('login', 'Admin\Auth\LoginController@showLoginForm')->name('admin.index');
    Route::post('login', 'Admin\Auth\LoginController@login')->name('admin.login');
    Route::post('logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');

    Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('/', 'Admin\IndexController@index');

            Route::post('saveManualImg', 'Admin\ManualsController@saveImg');

            Route::get('count', 'Admin\CountsController@index')->name('admin.counts.index');

            //Route::group(['middleware' => 'checkPermission'], function () {
                Route::resource('administrators', 'Admin\AdminsController')->names([
                    'index' => 'admin.administrators.index',
                    'store' => 'admin.administrators.store',
                    'create' => 'admin.administrators.create',
                    'destroy' => 'admin.administrators.destroy',
                    'update' => 'admin.administrators.update',
                    'show' => 'admin.administrators.show',
                    'edit' => 'admin.administrators.edit',
                ]);
                Route::resource('permissions', 'Admin\PermissionsController')->names([
                    'index' => 'admin.permissions.index',
                    'store' => 'admin.permissions.store',
                    'create' => 'admin.permissions.create',
                    'destroy' => 'admin.permissions.destroy',
                    'update' => 'admin.permissions.update',
                    'show' => 'admin.permissions.show',
                    'edit' => 'admin.permissions.edit',
                ]);
                Route::resource('roles', 'Admin\RolesController')->names([
                    'index' => 'admin.roles.index',
                    'store' => 'admin.roles.store',
                    'create' => 'admin.roles.create',
                    'destroy' => 'admin.roles.destroy',
                    'update' => 'admin.roles.update',
                    'show' => 'admin.roles.show',
                    'edit' => 'admin.roles.edit',
                ]);

                /*Route::get('user', 'Admin\UsersController@index')->name('admin.users.index');
                Route::get('user/{user}/edit', 'Admin\UsersController@edit')->name('admin.users.edit');
                Route::delete('user/{user}', 'Admin\UsersController@destroy')->name('admin.users.destroy');
                Route::post('user', 'Admin\UsersController@store')->name('admin.users.store');
                Route::put('user/{user}', 'Admin\UsersController@update')->name('admin.users.update');
                Route::get('user/create', 'Admin\UsersController@create')->name('admin.users.create');
                Route::get('user/{user}', 'Admin\UsersController@show')->name('admin.users.show');
                Route::get('users/address', 'Admin\UsersController@address')->name('admin.users.address');*/
                // 用户分类
                Route::resource('users', 'Admin\UsersController')->names([
                    'index' => 'admin.user.index',
                    'create' => 'admin.user.create',
                    'edit' => 'admin.user.edit',
                    'update' => 'admin.user.update',
                    'store' => 'admin.user.store',
                    'show' => 'admin.user.show',
                    'destroy' => 'admin.user.destroy'
                ]);

                //Route::get('users/entityList', 'Admin\UsersController@entityList')->name('admin.users.entityList');
                //Route::get('users/ajaxAddress', 'Admin\UsersController@ajaxAddress')->name('admin.users.ajaxAddress');
                //Route::post('users/latestPoint', 'Admin\UsersController@latestPoint')->name('admin.users.latestPoint');

                //Route::get('users/export', 'Admin\UsersController@export')->name('admin.users.export');

                //Route::get('convenientTask','Admin\ConvenientTaskController@index')->name('admin.convenientTask.index');
                //Route::get('convenientTask/create','Admin\ConvenientTaskController@create')->name('admin.convenientTask.create');

                // 用户分类
                Route::resource('userCategories', 'Admin\UserCategoriesController')->names([
                    'index' => 'admin.userCategory.index',
                    'create' => 'admin.userCategory.create',
                    'edit' => 'admin.userCategory.edit',
                    'update' => 'admin.userCategory.update',
                    'store' => 'admin.userCategory.store',
                    'show' => 'admin.userCategory.show',
                    'destroy' => 'admin.userCategory.destroy'
                ]);
                Route::resource('departments', 'Admin\DepartmentsController')->names([
                    'index' => 'admin.department.index',
                    'create' => 'admin.department.create',
                    'edit' => 'admin.department.edit',
                    'update' => 'admin.department.update',
                    'store' => 'admin.department.store',
                    'show' => 'admin.department.show',
                    'destroy' => 'admin.department.destroy'
                ]);

                // 通知消息
                Route::resource('messages', 'Admin\MessagesController', ['except' => ['edit', 'update']])->names([
                    'index' => 'admin.message.index',
                    'store' => 'admin.message.store',
                    'show' => 'admin.message.show',
                    'destroy' => 'admin.message.destroy',
                    'create' => 'admin.message.create'
                ]);

                // 日常任务
                Route::get('dailyTask/changeStatus/{dailyTask}', 'Admin\DailyTasksController@changeStatus')->name('admin.dailyTask.changeStatus'); // 修改任务状态
                Route::resource('dailyTasks', 'Admin\DailyTasksController', ['except' => ['edit', 'update']])->names([
                    'index' => 'admin.dailyTask.index',
                    'store' => 'admin.dailyTask.store',
                    'show' => 'admin.dailyTask.show',
                    'destroy' => 'admin.dailyTask.destroy',
                    'create' => 'admin.dailyTask.create'
                ]);
                Route::get('userDailyTasks/{user}/{dailyTask}', 'Admin\DailyTasksController@showUserList')->name('admin.dailyTask.userList');

                // 签到统计
                Route::get('signs', 'Admin\SignsController@index')->name('admin.sign.index');
                // 日常处理记录
                /*Route::resource('dailyProcesses', 'Admin\DailyProcessesController')->names([
                    'index' => 'admin.dailyProcess.index',
                    'create' => 'admin.dailyProcess.create',
                    'edit' => 'admin.dailyProcess.edit',
                    'update' => 'admin.dailyProcess.update',
                    'store' => 'admin.dailyProcess.store',
                    'show' => 'admin.dailyProcess.show',
                    'destroy' => 'admin.dailyProcess.destroy'
                ]);*/
                Route::any('dailyProcesses', 'Admin\DailyProcessesController@index')->name('admin.dailyProcess.index');
                Route::get('dailyProcesses/{dailyProcess}', 'Admin\DailyProcessesController@show')->name('admin.dailyProcess.show');
                Route::get('dailyProcesses/export/excel', 'Admin\DailyProcessesController@export')->name('admin.dailyProcess.export'); // 导出
                Route::delete('dailyProcesses/{dailyProcess}', 'Admin\DailyProcessesController@destroy')->name('admin.dailyProcess.destroy');

                Route::any('temporaryProcesses', 'Admin\TemporaryProcessesController@index')->name('admin.temporaryProcess.index');
                Route::get('temporaryProcesses/{commonProcess}', 'Admin\TemporaryProcessesController@show')->name('admin.temporaryProcess.show');
                Route::delete('temporaryProcesses/{commonProcess}', 'Admin\TemporaryProcessesController@destroy')->name('admin.temporaryProcess.destroy');
                Route::get('temporaryProcesses/export/excel', 'Admin\TemporaryProcessesController@export')->name('admin.temporaryProcess.export'); // 导出

                Route::any('specialProcesses', 'Admin\SpecialProcessesController@index')->name('admin.specialProcess.index');
                Route::get('specialProcesses/{commonProcess}', 'Admin\SpecialProcessesController@show')->name('admin.specialProcess.show');
                Route::delete('specialProcesses/{commonProcess}', 'Admin\SpecialProcessesController@destroy')->name('admin.specialProcess.destroy');
                Route::get('specialProcesses/export/excel', 'Admin\SpecialProcessesController@export')->name('admin.specialProcess.export'); // 导出


                // 临时任务
                Route::get('temporaryTask/changeStatus/{commonTask}', 'Admin\TemporaryTasksController@changeStatus')->name('admin.temporaryTask.changeStatus'); // 修改任务状态
                Route::resource('temporaryTasks', 'Admin\TemporaryTasksController', ['except' => ['edit', 'update', 'show', 'destroy']])->names([
                    'index' => 'admin.temporaryTask.index',
                    'store' => 'admin.temporaryTask.store',
                    //'show' => 'admin.temporaryTask.show',
                    //'destroy' => 'admin.temporaryTask.destroy',
                    'create' => 'admin.temporaryTask.create'
                ]);
                Route::get('temporaryTasks/{commonTask}', 'Admin\TemporaryTasksController@show')->name('admin.temporaryTask.show');
                Route::delete('temporaryTasks/{commonTask}', 'Admin\TemporaryTasksController@destroy')->name('admin.temporaryTask.destroy');

                // 专项任务
                Route::get('specialTask/changeStatus/{commonTask}', 'Admin\SpecialTasksController@changeStatus')->name('admin.specialTask.changeStatus');; // 修改任务状态
                Route::resource('specialTasks', 'Admin\SpecialTasksController', ['except' => ['edit', 'update', 'show', 'destroy']])->names([
                    'index' => 'admin.specialTask.index',
                    'store' => 'admin.specialTask.store',
                   // 'show' => 'admin.specialTask.show',
                    //'destroy' => 'admin.specialTask.destroy',
                    'create' => 'admin.specialTask.create'
                ]);
                Route::get('specialTasks/{commonTask}', 'Admin\SpecialTasksController@show')->name('admin.specialTask.show');
                Route::delete('specialTasks/{commonTask}', 'Admin\SpecialTasksController@destroy')->name('admin.specialTask.destroy');

                // 上报记录
                Route::resource('events', 'Admin\EventsController', ['except' => ['edit', 'update']])->names([
                    'index' => 'admin.event.index',
                    'store' => 'admin.event.store',
                    'show' => 'admin.event.show',
                    'destroy' => 'admin.event.destroy',
                    'create' => 'admin.event.create'
                ]);

                // 交班记录
                Route::resource('handoverRecords', 'Admin\HandoverRecordsController', ['except' => ['edit', 'update']])->names([
                    'index' => 'admin.handoverRecord.index',
                    'store' => 'admin.handoverRecord.store',
                    'show' => 'admin.handoverRecord.show',
                    'destroy' => 'admin.handoverRecord.destroy',
                    'create' => 'admin.handoverRecord.create'
                ]);

                // 小程序使用手册
                Route::get('manuals/updateSort/{manual}', 'Admin\ManualsController@updateSort')->name('admin.manual.updateSort');
                Route::resource('manuals', 'Admin\ManualsController')->names([
                    'index' => 'admin.manual.index',
                    'store' => 'admin.manual.store',
                    'show' => 'admin.manual.show',
                    'destroy' => 'admin.manual.destroy',
                    'create' => 'admin.manual.create',
                    'edit' => 'admin.manual.edit',
                    'update' => 'admin.manual.update'
                ]);

            //});

            // 导入导出
           // Route::get('matters/export', 'Admin\MattersController@export')->name('admin.matters.export');
           // Route::post('matters/import', 'Admin\MattersController@import')->name('admin.matters.import');
           // Route::get('matters/download', 'Admin\MattersController@download')->name('admin.matters.download');



        // 统计
       // Route::get('count', 'Admin\CountsController@index')->name('admin.counts.index');

        // 导出报表
      //  Route::get('export', 'Admin\CountsController@export')->name('admin.counts.export');

       // Route::get('allUserPatrol', 'Admin\CountsController@allUserPatrol')->name('admin.counts.allUserPatrol');

      //  Route::get('dataInfo', 'Admin\CountsController@dataInfo')->name('admin.counts.dataInfo');

    });

});
