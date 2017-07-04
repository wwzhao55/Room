<!DOCTYPE html>
<html>
<hea
d>
<meta name="viewport" content="width=750, user-scalable=no">
<title>发布</title>
	<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="/room/js/jquery-2.2.3.min.js"></script>
	<script src="/room/weui/js/jquery-weui.min.js"></script>
	<script src="https://api.map.baidu.com/api?v=2.0&ak=p8e9UGNDBNR2fn16v0dH8kPwEZFBtTPb" type="text/javascript"></script>
	<script type="text/javascript" src="/room/js/publish.js"></script>
	<link rel="stylesheet" href="/room/weui/lib/weui.min.css">
	<link rel="stylesheet" href="/room/weui/css/jquery-weui.min.css">
	<link href="/room/css/vip.css" rel="stylesheet">
	
</head>
<body>
<div class="area_list">
	<input id="suggestId1" type="text" placeholder="请搜索您的小区或大厦、街道名称">
	<div id="l-map" style="display:none;"></div>
	<div id="r-result"></div>
	<!-- <div id="searchResultPanel"></div>	 -->
</div>
<div class="roombody">
	<div class="publish_room_message">
		<!-- <div class="publish_room_image">
			<input class="upload_pic_main" type="file" name="" hidden >
			<img src="{{asset('/image/publish/pic@2x.png')}}" class="add_image">
		</div> -->
		<div class="publish_search">
			<img src="{{asset('/image/publish/search@2x.png')}}">
			<input id="suggestId" type="text" placeholder="请搜索您的小区或大厦、街道名称">					
		</div>
		<div class="publish_number">
			<img src="{{asset('/image/publish/number@2x.png')}}">
			<input type="text" placeholder="请填写您的门牌号">
			<div class="number_tip">门牌号信息仅用于数据统计，不会展示给其他用户</div>
		</div>
	</div>
	
	<div class="publish_remark">
		<div class="publish_person">
			<div class="person_lists">
				<img src="{{asset('/image/publish/phone@2x.png')}}" class="signal">
				@if($phone)
				<input type="text" class="person_phone" value="{{$phone}}" readonly>
			</div>
				@else
				<input type="text" placeholder="请输入您的手机号" class="person_phone">
			</div>
			<div class="person_lists">
				<img src="{{asset('/image/publish/password@2x.png')}}" class="signal">
				<input type="text" placeholder="请输入您的验证码" class="person_code" style="width:385px;">
				<button class="btn_code btn_code_disabled">获取验证码</button>
			</div>
				@endif
			
			<div class="person_lists">
				<img src="{{asset('/image/publish/money@2x.png')}}" class="signal">
				<input type="text" placeholder="请输入租金" class="person_rent">
			</div>
			<div class="person_lists">
				<img src="{{asset('/image/publish/area@2x.png')}}" class="signal">
				<select class="person_direction" value="">			
					<option value="0">房屋朝向</option>
					<option value="朝阳">朝阳</option>
					<option value="不朝阳">不朝阳</option>
				</select>
				<img src="{{asset('/image/publish/more@2x.png')}}" class="more">
			</div>
			<div class="person_lists">
				<img src="{{asset('/image/publish/area@2x.png')}}" class="signal">
				<select class="person_area" value="">			
					<option value="0">请选择面积</option>
					@foreach($area as $list)
					<option value="{{$list->id}}">{{$list->area}}</option>
					@endforeach
				</select>
				<img src="{{asset('/image/publish/more@2x.png')}}" class="more">
			</div>
			<div class="person_lists person_lists_last">
				<img src="{{asset('/image/publish/apartment layout@2x.png')}}" class="signal">
				<select class="person_type" value="">
					<option value="0">请选择户型</option>
					@foreach($type as $list)
					<option value="{{$list->id}}">{{$list->type}}</option>
					@endforeach
				</select>
				<img src="{{asset('/image/publish/more@2x.png')}}" class="more">
			</div>
		</div>
		<div class="remark_lists">
			<div class="remark_head">
				<hr class="remark_line">
				<span>吐槽</span>
				<img src="{{asset('/image/publish/pic@2x.png')}}" class="bad_add_image">
				<!-- <input class="upload_pic_bad" type="file" hidden > -->
			</div>
			<div class="bad_img_lists"></div>
			<textarea class="remark_detail bad_text" placeholder="槽点是不是特别多啊，请写在这里吧，让其他人不再被套路"></textarea>
		</div>
		<div class="remark_lists">
			<div class="remark_head">
				<hr class="remark_line">
				<span>点赞</span>
				<img src="{{asset('/image/publish/pic@2x.png')}}" class="good_add_image">
				<!-- <input class="upload_pic_good" type="file" hidden > -->
			</div>
			<div class="good_img_lists"></div>
			<textarea class="remark_detail good_text" placeholder="有想点赞的可以写在这里"></textarea>
		</div>
		<div class="remark_lists remark_lists_last">
			<div class="remark_head">
				<hr class="remark_line">
				<span>点评</span>
			</div>
			<div class="remark_level">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
			</div>
		</div>
		<button class="btn_publish">确定</button>
	</div>
</div>
<div class="roomfoot">
	<a href="{{ URL::asset('index')}}"><div><img src="{{asset('/image/main/tabbar_icon_home_default@2x.png')}}"><br><span>首页</span></div></a>
	<a href="{{ URL::asset('publish')}}"><div class="onactive"><img src="{{asset('/image/main/tabbar_icon_publish_pressed@2x.png')}}"><br><span>发布</span></div></a>
	<a href="{{ URL::asset('myinfo')}}"><div><img src="{{asset('/image/main/tabbar_icon_mine_default@2x.png')}}"><br><span>我的</span></div></a>
</div>	
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1261409102).'" width="0" height="0"/>';?>
</body>
</html>