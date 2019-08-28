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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'admin'], function () {

    Route::get('login', 'Admin\Auth\LoginController@showLoginForm')->name('admin.index');
    Route::post('login', 'Admin\Auth\LoginController@login')->name('admin.login');
    Route::post('logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/', 'Admin\IndexController@index');

        Route::get('user', 'Admin\UsersController@index')->name('admin.users.index');
        Route::get('user/{user}/edit', 'Admin\UsersController@edit')->name('admin.users.edit');
        Route::delete('user/{user}', 'Admin\UsersController@destroy')->name('admin.users.destroy');
        Route::post('user', 'Admin\UsersController@store')->name('admin.users.store');
        Route::put('user/{user}', 'Admin\UsersController@update')->name('admin.users.update');
        Route::get('user/create', 'Admin\UsersController@create')->name('admin.users.create');
        Route::get('user/{user}', 'Admin\UsersController@show')->name('admin.users.show');
        Route::get('users/address', 'Admin\UsersController@address')->name('admin.users.address');


        Route::get('convenientTask','Admin\ConvenientTaskController@index')->name('admin.convenientTask.index');
        Route::get('convenientTask/create','Admin\ConvenientTaskController@create')->name('admin.convenientTask.create');

        Route::resource('entities', 'Admin\EntitiesController',  ['except' => ['destroy', 'show', 'create', 'update', 'edit']])->names([
            'index' => 'admin.entities.index',
            'store' => 'admin.entities.store',
            //'create' => 'admin.entities.create',
            //'update' => 'admin.entities.update',
            //'show' => 'admin.entities.show',
            //'edit' => 'admin.entities.edit',
        ]);

        Route::get('entities/{entity_name}/destroy', 'Admin\EntitiesController@destroy')->name('admin.entities.destroy');






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
        // 责任类别
        Route::resource('categories', 'Admin\CategoriesController', ['except' => ['show']])->names([
            'index'     =>  'admin.categories.index',
            'create'    =>  'admin.categories.create',
            'store'     =>  'admin.categories.store',
            'edit'      =>  'admin.categories.edit',
            'update'    =>  'admin.categories.update',
            'destroy'   =>  'admin.categories.destroy',
        ]);
        // 责任清单
        Route::resource('responsibility', 'Admin\ResponsibilityController', ['except' => ['show']])->names([
            'index'     =>  'admin.responsibility.index',
            'create'    =>  'admin.responsibility.create',
            'store'     =>  'admin.responsibility.store',
            'edit'      =>  'admin.responsibility.edit',
            'update'    =>  'admin.responsibility.update',
            'destroy'   =>  'admin.responsibility.destroy',
        ]);
        // 任务清单
        Route::resource('matters', 'Admin\MattersController', ['except' => ['show']])->names([
            'index'     =>  'admin.matters.index',
            'create'    =>  'admin.matters.create',
            'store'     =>  'admin.matters.store',
            'edit'      =>  'admin.matters.edit',
            'update'    =>  'admin.matters.update',
            'destroy'   =>  'admin.matters.destroy',
        ]);
        // 城市部件信息
        Route::resource('part', 'Admin\PartsController', ['except' => ['show']])->names([
            'index'     =>  'admin.part.index',
            'create'    =>  'admin.part.create',
            'store'     =>  'admin.part.store',
            'edit'      =>  'admin.part.edit',
            'update'    =>  'admin.part.update',
            'destroy'   =>  'admin.part.destroy',
        ]);
        // 城市部件地图路由
        Route::get('part/grid', 'Admin\PartsController@grid')->name('admin.part.grid');
        Route::get('part/marker', 'Admin\PartsController@marker')->name('admin.part.marker');
        Route::get('part/mapInfo', 'Admin\PartsController@mapInfo')->name('admin.part.mapInfo');

        // 任务情况
        Route::resource('situations', 'Admin\SituationsController')->names([
            'index'     =>  'admin.situations.index',
            'create'    =>  'admin.situations.create',
            'store'     =>  'admin.situations.store',
            'edit'      =>  'admin.situations.edit',
            'update'    =>  'admin.situations.update',
            'destroy'   =>  'admin.situations.destroy',
        ]);

        // 分配任务到人
        Route::get('matters/users', 'Admin\MattersController@getUser')->name('admin.matters.users');
        // 此路由为分配到人，表格头的按钮，以注释，后面不需要则删除
        Route::post('matters/mtu', 'Admin\MattersController@mattersToUser')->name('admin.matters.mtu');

        Route::get('matters/allocate', 'Admin\MattersController@allocate')->name('admin.matters.allocate');
        Route::post('matters/allocates', 'Admin\MattersController@allocates')->name('admin.matters.allocates');

        // 导入导出
        Route::get('matters/export', 'Admin\MattersController@export')->name('admin.matters.export');
        Route::post('matters/import', 'Admin\MattersController@import')->name('admin.matters.import');
        Route::get('matters/download', 'Admin\MattersController@download')->name('admin.matters.download');
    });

});
