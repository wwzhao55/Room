<!-- auth:wwj
	 date:2017.02.09 
-->
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=750, user-scalable=no">
<title>我的</title>
<link rel="stylesheet" href="/room/weui/lib/weui.min.css">
<link rel="stylesheet" href="/room/weui/css/jquery-weui.min.css">
<link href="/room/css/vip.css" rel="stylesheet">
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="/room/js/jquery-2.2.3.min.js"></script>
<script src="/room/weui/js/jquery-weui.min.js"></script>
<script src="/room/js/layer/layer.js"></script>
<script type="text/javascript" src="/room/js/myinfo.js"></script>
</head>

<body>
<div class="roombody">
	<div class="head">
		<img src="{{asset($headimg)}}" class="headimg">
	</div>
	<div class="vip_message">
		<div class="nickname">{{$name}}</div>
		@if($phone)
		<div class="phone_number">{{$phone}}</div>
		@else
		<div class="phone_number"></div>
		@endif
		<img src="{{asset('/image/myinfo/btn_modify_default@2x.png')}}" class="modify_number">
	</div>
	<a href="/room/myinfo/collect"><div class="detail">
		<div>我收藏的</div>
		<img src="{{asset('/image/myinfo/collect_icon_collect_default@2x.png')}}">
	</div></a>
	<a href="/room/myinfo/publish"><div class="detail">
		<div>我发布的</div>
		<img src="{{asset('/image/myinfo/tabbar_icon_publish_default@2x.png')}}">
	</div></a>
	<a href="/room/myinfo/comment"><div class="detail">
		<div>我评论的</div>
		<img src="{{asset('/image/myinfo/comment_icon_comment_default@2x.png')}}">
	</div></a>
	@if(Auth::user()->is_admin)
	<a href="/room/index/1"><div class="detail">
		<div class="manage">管理</div>
		<!-- <div class="finish_manage" hidden>完成管理</div> -->
	</div></a>
	@endif
</div>
<div id="layer_modify">
	<div class="layer_title">修改手机号码</div>
	<input type="number" class="layer_input" autofocus>
	<div class="no_tip" hidden>请输入手机号码</div>
	<button class="layer_cancel">取消</button>
	<button class="layer_confirm">确定</button>
</div>
<div class="roomfoot">
	<a href="{{ URL::asset('index')}}"><div><img src="{{asset('/image/main/tabbar_icon_home_default@2x.png')}}"><br><span>首页</span></div></a>
	<a href="{{ URL::asset('publish')}}"><div><img src="{{asset('/image/main/tabbar_icon_publish_default@2x.png')}}"><br><span>发布</span></div></a>
	<a href="{{ URL::asset('myinfo')}}"><div class="onactive"><img src="{{asset('/image/main/tabbar_icon_mine_pressed@2x.png')}}"><br><span>我的</span></div></a>
</div>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1261409102).'" width="0" height="0"/>';?>
</body>
</html>