<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=750, user-scalable=no">
<title>首页</title>
<link rel="stylesheet" href="/room/weui/lib/weui.min.css">
<link rel="stylesheet" href="/room/weui/css/jquery-weui.min.css">
<link href="/room/css/dropload.css" rel="stylesheet">
<link href="/room/css/vip.css" rel="stylesheet">
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<!-- <script type="text/javascript" src="/room/js/jquery.min.js"></script> -->
<script type="text/javascript" src="/room/js/jquery-2.2.3.min.js"></script>
<script src="/room/weui/js/jquery-weui.min.js"></script>

<script type="text/javascript" src="/room/weui/js/city-picker.js"></script>
<script src="https://api.map.baidu.com/api?v=2.0&ak=p8e9UGNDBNR2fn16v0dH8kPwEZFBtTPb" type="text/javascript"></script>
<script type="text/javascript" src="/room/js/dropload.js"></script>
<script src="/room/js/layer/layer.js"></script>
<script type="text/javascript" src="/room/js/main.js"></script>
</head>
<body>
<div id="layer_address">
	<div class="layer_title">切换位置</div>
	<div class="select_btn">
		<button class="address_city btn_pressed">切换城市</button>
		<button class="address_district">切换区域</button>
	</div>
	<input type="text" class="layer_input1" placeholder="请选择城市">
	<input type="text" class="layer_input2" placeholder="请选择地区" hidden>
	<div class="select_btn">
		<button class="layer_cancel">取消</button>
		<button class="layer_confirm">确定</button>
	</div>
	
</div>
<div id="layer_delete">
	<div class="delete_title">确认删除该房屋？</div>
	<button class="delete_cancel">取消</button>
	<button class="delete_confirm">确认</button>
</div>
<div class="roomhead">
	<div>
		<span class="current_area"></span>
		<img src="{{asset('/image/main/down_icon_pull_default@2x.png')}}" class="down_icon">
		<img src="{{asset('/image/main/search_icon_search_default@2x.png')}}" class="search_icon">
		<form action="#">
			<input type="search" class="search_input" id="search_input" placeholder="输入您想住的区域或小区">
		</form>
	</div>
	<div class="select_type">
		<div class="onactive">推荐</div>
		<div>热点</div>
		<div>附近</div>
	</div>
</div>
<div class="roombody">
	
</div>
<div class="roomfoot">
	<a href="{{ URL::asset('index')}}"><div class="onactive"><img src="{{asset('/image/main/tabbar_icon_home_pressed@2x.png')}}"><br><span>首页</span></div></a>
	<a href="{{ URL::asset('publish')}}"><div><img src="{{asset('/image/main/tabbar_icon_publish_default@2x.png')}}"><br><span>发布</span></div></a>
	<a href="{{ URL::asset('myinfo')}}"><div><img src="{{asset('/image/main/tabbar_icon_mine_default@2x.png')}}"><br><span>我的</span></div></a>
</div>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1261409102).'" width="0" height="0"/>';?>
</body>
</html>