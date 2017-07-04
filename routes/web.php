<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri=https://shop.dataguiding.com/room/auth&response_type=code&scope=snsapi_userinfo#wechat_redirect');
});

Route::get('auth/detail/{room}', function ($room) {
    return redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri=https://shop.dataguiding.com/room/auth&response_type=code&state='.$room.'&scope=snsapi_userinfo#wechat_redirect');
});
Route::get('auth','OauthController@index');

Route::group(['middleware' => 'oauth'], function () {
    Route::get('index','MainController@index');
    Route::get('house-list','MainController@houseList');//获取房屋列表

	Route::get('publish','PublishController@index');
	Route::post('publish/submit','PublishController@submit');//提交发布
	Route::post('publish/send-code','PublishController@sendcode');//发送验证码


	Route::get('detail/{room}','DetailController@index');
	Route::post('detail/send-comment','DetailController@sendComment');//发表评论
	Route::post('detail/like-comment','DetailController@likeComment');//点赞评论
	Route::post('detail/collect','DetailController@collectHouse');//收藏
	Route::post('detail/like-house','DetailController@likeHouse');//点赞房屋


	Route::get('myinfo','MyinfoController@index');
	Route::get('myinfo/collect','MyinfoController@mycollect');//我收藏的
	Route::get('myinfo/collect-list','MyinfoController@getMycollect');//我收藏的

	Route::get('myinfo/publish','MyinfoController@mypublish');//我发布的
	Route::get('myinfo/publish-list','MyinfoController@getMypublish');//我发布的

	Route::get('myinfo/comment','MyinfoController@mycomment');//我评论的
	Route::get('myinfo/comment-list','MyinfoController@getMycomment');//我评论的

	Route::post('myinfo/change-phone','MyinfoController@changePhone');//修改手机号

	//数据库维护
	Route::post('publish/add-area','PublishController@addArea');
	Route::post('publish/add-type','PublishController@addType');

	//管理员
	Route::post('admin/delete-house','AdminController@deleteHouse');//删除房屋
	Route::post('admin/delete-comment','AdminController@deleteComment');//删除评论
	Route::get('index/{is_admin}','MainController@index');
	Route::get('detail/{room}/{is_admin}','DetailController@index');
});


