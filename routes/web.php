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

Route::get('/',['as' => 'home','uses' => 'UserController@home']);

Route::any('adminLog',['as' => 'adminLog','uses' => 'ManagerController@login']);
Route::get('viewAssociationBackground/{id}',['as' => 'viewAssociationBackground','uses' => 'ManagerController@viewAssociationBackground']);
Route::get('viewDepartmentBackground/{id}',['as' => 'viewDepartmentBackground','uses' => 'ManagerController@viewDepartmentBackground']);
Route::get('viewAssociationLogo/{id}',['as' => 'viewAssociationLogo','uses' => 'ManagerController@viewAssociationLogo']);
Route::any('userLog',['as' => 'userLog','uses' => 'UserController@login']);
Route::any('userRegister',['as' => 'userRegister','uses' => 'UserController@register']);
Route::any('userResetPassword',['as' => 'userResetPassword','uses' => 'UserController@resetPassword']);
Route::any('managerResetPassword',['as' => 'managerResetPassword','uses' => 'ManagerController@resetPassword']);
Route::get('viewUserPhoto/{id}',['as' => 'viewUserPhoto','uses' => 'UserController@viewPhoto']);
Route::get('association/{id?}',['as' => 'association','uses' => 'ManagerController@association']);
Route::get('department/{id?}',['as' => 'department','uses' => 'ManagerController@department']);
Route::post('captcha4SendSMS',['as' => 'captcha4SendSMS','uses' => 'UserController@captcha4SendSMS']);

Route::group(['middleware' => ['user']],function()
{
    Route::get('userLogout',['as' => 'userLogout','uses' => 'UserController@logout']);
    Route::get('userInfo',['as' => 'userInfo','uses' => 'UserController@userInfo']);
    Route::post('varyUser',['as' => 'varyUser','uses' => 'UserController@varyUser']);
    Route::any('apply/{id}',['as' => 'apply' ,'uses' => 'UserController@apply']);
    Route::get('userApplication/{id?}',['as' => 'userApplication','uses' => 'UserController@myApplication']);
    Route::post('uploadUserPhoto',['as' => 'uploadUserPhoto','uses' => 'UserController@uploadPhoto']);
});

Route::group(['middleware' => ['manager']],function()
{
    Route::get('admin',['as' => 'admin','uses' => 'ManagerController@index']);
    Route::get('adminLogout',['as' => 'adminLogout','uses' => 'ManagerController@logout']);
    Route::get('teacher',['as' => 'teacher','uses' => 'ManagerController@teacher']);
    Route::any('createManager',['as' => 'createManager','uses' => 'ManagerController@createManager']);
    Route::any('createAssociation',['as' => 'createAssociation','uses' => 'ManagerController@createAssociation']);
    Route::any('createDepartment',['as' => 'createDepartment','uses' => 'ManagerController@createDepartment']);
    Route::any('varyAssociation',['as' => 'varyAssociation','uses' => 'ManagerController@varyAssociation']);
    Route::any('varyDepartment',['as' => 'varyDepartment','uses' => 'ManagerController@varyDepartment']);
    Route::get('myDepartment',['as' => 'myDepartment','uses' => 'ManagerController@myDepartment']);
    Route::any('addManager',['as' => 'addManager','uses' => 'ManagerController@addManager']);
    Route::get('myManager',['as' => 'myManager','uses' => 'ManagerController@myManager']);
    Route::any('chooseDepartment',['as' => 'chooseDepartment','uses' => 'ManagerController@chooseDepartment']);
    Route::get('manager/{id?}',['as' => 'manager','uses' => 'ManagerController@manager']);
    Route::any('varyManager',['as' => 'vary','uses' => 'ManagerController@varyManager']);
    Route::post('upgradeManager',['as' => 'upgradeManaer','uses' => 'ManagerController@upgradeManager']);
    Route::get('departmentApplication/{id?}',['as' => 'departmentApplication','uses' => 'ManagerController@departmentApplication']);
    Route::get('associationApplication',['as' => 'associationApplication','uses' => 'ManagerController@associationApplication']);
    Route::post('nextRound',['as' => 'nextRound','uses' => 'ManagerController@nextRound']);
    Route::post('pass',['as' => 'pass','uses' => 'ManagerController@pass']);
    Route::post('note',['as' => 'note','uses' => 'ManagerController@note']);
    Route::post('finish',['as' => 'finish','uses' => 'ManagerController@finish']);
    Route::get('getAssociationDataExcel',['as' => 'getAssociationDataExcel','uses' => 'ManagerController@getAssociationDataExcel']);
    Route::get('getDataExcel',['as' => 'getDataExcel','uses' => 'ManagerController@getDataExcel']);
    Route::get('getSignExcel',['as' => 'getSignExcel','uses' => 'ManagerController@getSignExcel']);
    Route::get('getInterviewExcel',['as' => 'getInterviewExcel','uses' => 'ManagerController@getInterviewExcel']);
    Route::post('uploadAssociationBackground',['as' => 'uploadAssociationBackground','uses' => 'ManagerController@uploadAssociationBackground']);
    Route::post('uploadDepartmentBackground',['as' => 'uploadDepartmentBackground','uses' => 'ManagerController@uploadDepartmentBackground']);
    Route::post('uploadAssociationLogo',['as' => 'uploadAssociationLogo','uses' => 'ManagerController@uploadAssociationLogo']);
    Route::post('sendStartSMS',['as' => 'sendStartSMS','uses' => 'ManagerController@sendStartSMS']);
    Route::post('sendNextSMS',['as' => 'sendNextSMS','uses' => 'ManagerController@sendNextSMS']);
    Route::post('sendPassSMS',['as' => 'sendPassSMS','uses' => 'ManagerController@sendPassSMS']);
});

/*
 * 管理员分类
 * 1为超级管理员
 * 2为老师领导
 * 3为社团老大
 * 4为部门部长