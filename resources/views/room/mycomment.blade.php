<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=750, user-scalable=no">
<title>我评论的</title>
<link rel="stylesheet" href="/room/weui/lib/weui.min.css">
<link rel="stylesheet" href="/room/weui/css/jquery-weui.min.css">
<link href="/room/css/dropload.css" rel="stylesheet">
<link href="/room/css/vip.css" rel="stylesheet">
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="/room/js/jquery-2.2.3.min.js"></script>
<script src="/room/weui/js/jquery-weui.min.js"></script>
<script type="text/javascript" src="/room/js/dropload.js"></script>
<script type="text/javascript" src="/room/js/mycomment.js"></script>
</head>
<body>
<div class="roombody">
	
</div>
<div class="kongbai"></div>
<div class="roomfoot">
	<a href="{{ URL::asset('index')}}"><div><img src="{{asset('/image/main/tabbar_icon_home_default@2x.png')}}"><br><span>首页</span></div></a>
	<a href="{{ URL::asset('publish')}}"><div><img src="{{asset('/image/main/tabbar_icon_publish_default@2x.png')}}"><br><span>发布</span></div></a>
	<a href="{{ URL::asset('myinfo')}}"><div class="onactive"><img src="{{asset('/image/main/tabbar_icon_mine_pressed@2x.png')}}"><br><span>我的</span></div></a>
</div>
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1261409102).'" width="0" height="0"/>';?>
</body>
</html>