<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function ($api) {
    $api->get('version',function () {
        return response('this is version v1');
    });

    // 登录
    $api->post('authorizations', 'AuthorizationsController@store')
        ->name('api.authorizations.store');
    // 小程序登录
    $api->post('weappAuthorizations', 'AuthorizationsController@weappStore');
    // 刷新token
    $api->put('authorizations/current', 'AuthorizationsController@update')
        ->name('api.authorizations.update');
    // 删除token
    $api->delete('authorizations/destroy', 'AuthorizationsController@delete')
        ->name('api.authorizations.delete');

    $api->post('storeUser', 'UserMessageController@UserMessageStore');
    $api->get('getCategoryAndDepartment', 'UserMessageController@getCategoryAndDepartment');

    //$api->post('wuthorizations', 'AuthorizationsController@weappStore');
    $api->get('categories', 'CategoryController@categories');  //责任清单分类
    $api->group(['middleware' => 'refresh.token'], function ($api) {
        // 图片上传接口
        $api->post('uploadImg', 'FileUploadController@saveImg');
        // 未读消息，未完成任务数量
        $api->get('count', 'RepairsController@count');
        // 日常任务列表
        $api->get('dailyTasks', 'DailyTaskController@index');
        // 日常任务上报
        $api->post('dailyTasks', 'DailyTaskController@store');
        // 专项任务列表
        $api->get('specialTasks', 'CommonTaskController@specialTasks');
        // 临时任务列表
        $api->get('temporaryTasks', 'CommonTaskController@temporaryTasks');
        // 专项和临时任务上报
        $api->post('commonTasks', 'CommonTaskController@store');
        // 上报事件
        $api->post('uploadEvent', 'UploadEventController@eventStore');
        // 消息列表
        $api->get('messages/list', 'MessageController@messageList');
        // 读取消息
        $api->get('messages/read', 'MessageController@readMessage');
        // 已完成任务
        $api->get('complete/list', 'UserController@completeList');
        // 未完成任务
        $api->get('undone/list', 'UserController@undoneList');
        // 上报记录
        $api->get('uploadEvent/list', 'UserController@uploadEvent');
        //  用户状态
        $api->get('getUserStatus', 'SignController@userStatus');
        //  用户签到
        $api->get('userSign', 'SignController@sign');
        // 交班内容
        $api->get('handoverRecord/records', 'HandoverRecordController@records');
        // 获取交接用户
        $api->get('handoverRecord/getUsers', 'HandoverRecordController@getUsers');
        //  上报交班内容
        $api->post('handoverRecord/storeHandover', 'HandoverRecordController@storeHandover');
        //  设交班内容为已读
        $api->get('handoverRecord/readHandover', 'HandoverRecordController@readHandover');
        //  使用手册
        $api->get('manual/manualList', 'ManualController@index');


        $api->get('user', 'RepairsController@thisUser');

        $api->get('repairs', 'RepairsController@repairs');  // 报修列表

        $api->post('eventReport', 'RepairsController@eventReport');  //报修

        $api->get('repairDetail/{id}', 'RepairsController@repairDetail');  // 报修详情

        $api->post('completeRepair', 'RepairsController@completeRepair'); //完成报修
    });
    // 上传版本
    $api->post('fileUpload','FileUploadController@save')->name('api.fileUpload.save');



    $api->group(['middleware' => 'auth:programApi'], function ($api) {

    });



});
