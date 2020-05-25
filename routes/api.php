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

    //$api->post('wuthorizations', 'AuthorizationsController@weappStore');
    $api->get('categories', 'CategoryController@categories');  //责任清单分类
    $api->group(['middleware' => 'refresh.token'], function ($api) {
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

        $api->get('user', 'RepairsController@thisUser');

        $api->get('repairs', 'RepairsController@repairs');  // 报修列表

        $api->post('eventReport', 'RepairsController@eventReport');  //报修

        $api->get('repairDetail/{id}', 'RepairsController@repairDetail');  // 报修详情

        $api->post('completeRepair', 'RepairsController@completeRepair'); //完成报修

        $api->get('userHasMatters', 'MattersController@userHasMatters'); //用户未完成任务

        $api->get('userCompleteMatters', 'MattersController@userCompleteMatters'); //用户已完成任务

        $api->get('userMatters', 'MattersController@userMatters'); //用户全部任务

        $api->get('matter', 'MattersController@matter');   //任务详情

        //$api->get('categories', 'CategoryController@categories');  //责任清单分类

        $api->post('endMatter', 'MattersController@endImportMatter');   //完成12345任务

        $api->get('patrolList', 'PatrolController@patrolList');   //巡查记录

        $api->post('importMatter', 'MattersController@findMatterAndEnd');  //发现并提交问题

        $api->get('patrolMatters', 'MattersController@patrolMatters'); //用户巡查中上报的全部任务

        $api->post('startAndEndPatrol', 'PatrolController@startAndEndPatrol');  //开始和结束巡逻

        // 警报 alarm
        $api->get('userHasAlarms', 'AlarmsController@userHasAlarms');   // 告警任务派发列表
        $api->post('completeAlarm', 'AlarmsController@completeAlarm');  // 告警完成
    });

    $api->post('alarm', 'AlarmsController@alarm');  // 摄像头告警

    $api->post('helmetAlarm', 'HatsController@helmetAlarm'); // 安全帽检测

    $api->get('carouselMap', 'ProgramImagesController@carouselMap');

    // 公开隐藏
    $api->get('openMatters', 'ProgramImagesController@matters');
    // 要闻信息
    $api->get('news', 'NewsController@index');
    // 详情
    $api->get('newsDetail/{id}', 'NewsController@newsDetail');

    // 上传版本
    $api->post('fileUpload','FileUploadController@save')->name('api.fileUpload.save');

    $api->get('version','VersionsController@version');

    $api->post('zr','VersionsController@zr');

    $api->group(['middleware' => 'auth:programApi'], function ($api) {
        $api->post('matterStore', 'ProgramUsersController@matterStore');  // 上报问题
        $api->post('matterStoreInfo', 'ProgramUsersController@matterStoreInfo'); // 上报问题信息
        $api->post('matterStoreImage', 'ProgramUsersController@matterStoreImage'); // 上报问题图片
        $api->get('historyMatters', 'ProgramUsersController@historyMatters');  //历史提交问题记录
        $api->get('historyMattersDetail/{id}', 'ProgramUsersController@historyMattersDetail'); // 历史提交问题详情
        $api->get('weappUser', 'ProgramUsersController@weappUser');  // 当前用户信息
        $api->post('newsComment', 'NewsController@comment'); // 评论
        $api->post('destroyComment', 'NewsController@destroyComment'); // 删除评论
    });



});
