<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=750, user-scalable=no">
<title>详情</title>	
	<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="/room/js/jquery-2.2.3.min.js"></script>
	<script src="/room/weui/js/jquery-weui.min.js"></script>
	<script src="/room/js/layer/layer.js"></script>
	<script type="text/javascript" src="/room/js/detail.js"></script>
	<link rel="stylesheet" href="/room/weui/lib/weui.min.css">
	<link rel="stylesheet" href="/room/weui/css/jquery-weui.min.css">
	<link href="/room/css/vip.css" rel="stylesheet">
	
</head>
<body>
<div class="roombody">
	<div id="mcover" style="display: none;">
	     <img src="{{asset('/image/detail/Prompt1@2x.png')}}">
	</div>
	<div class="house_id" hidden>{{$house->id}}</div>
	<div class="publish_room_message1">
		<!-- <div class="publish_room_image">
			<img src="/room{{$house->main_img}}" class="img_list preview">
		</div> -->
		<div class="publish_search">
			<img src="{{asset('/image/publish/search@2x.png')}}">
			<input id="suggestId" type="text" value="{{$house->name}}{{$house->province}}{{$house->city}}{{$house->district}}{{$house->address}}" readonly>					
		</div>
	</div>	
	<div class="publish_remark">		
		<div class="publish_person" style="height:240px;">
			<div class="person_lists">
				<div class="content_tip">
					<img src="{{asset('/image/publish/money@2x.png')}}" class="signal">
					<span class="tip_content">月租</span>
				</div>
				<input type="text" value="{{$house->rent}}" readonly class="person_rent" style="text-align: right;width:415px;">
			</div>
			<div class="person_lists">
				<div class="content_tip">
					<img src="{{asset('/image/publish/area@2x.png')}}" class="signal">
					<span class="tip_content">房屋朝向</span>
				</div>				
				<input class="person_direction" value="{{$house->orientation}}" type="text" readonly style="text-align: right;width:415px;">			
			</div>
			<div class="person_lists">
				<div class="content_tip">
					<img src="{{asset('/image/publish/area@2x.png')}}" class="signal">
					<span class="tip_content">面积</span>
				</div>
				<input class="person_area" value="{{$house->area}}" type="text" readonly style="text-align: right;width:415px;">			
			</div>
			<div class="person_lists person_lists_last">
				<div class="content_tip">
					<img src="{{asset('/image/publish/apartment layout@2x.png')}}" class="signal">
					<span class="tip_content">户型</span>
				</div>				
				<input class="person_type" value="{{$house->type}}" type="text" readonly style="text-align: right;width:415px;">
			</div>
		</div>
		<div class="remark_lists remark_lists_last">
			<div class="remark_head">
				<hr class="remark_line">
				<span>吐槽</span>
			</div>
			<div class="bad_text_div" >{{$house->bad_text}}</div>
			<div class="bad_img_lists1">
			@if($house->bad_img)
				@foreach($house->bad_img as $list)
					<img src="/room{{$list}}" class="bad_img_list1 preview">
				@endforeach
			@endif
			</div>			
		</div>
		<div class="remark_lists remark_lists_last">
			<div class="remark_head">
				<hr class="remark_line">
				<span>点赞</span>
			</div>
			<div class="good_text_div">{{$house->good_text}}</div>
			<div class="good_img_lists1">
			@if($house->good_img)
				@foreach($house->good_img as $list)
					<img src="/room{{$list}}" class="good_img_list1 preview">
				@endforeach
			@endif
			</div>			
		</div>
		<div class="remark_lists remark_lists_last">
			<div class="remark_head">
				<hr class="remark_line">
				<span>点评</span>
			</div>
			<div class="remark_level">
				<div class="remark_score" hidden>{{$house->score}}</div>
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				@if($house->score==1)
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				@elseif($house->score==2)
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				@elseif($house->score==3)
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				@elseif($house->score==4)
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star@2x.png')}}" class="star">
				@elseif($house->score==5)
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				<img src="{{asset('/image/publish/star1@2x.png')}}" class="star">
				@endif
				<button class="btn-delete2 delete_house">删除</button>
			</div>
		</div>
		<a name="comment" id="comment"></a>
		@foreach($house->comments as $list)
		<div class="user_remark_list">
			<div class="comment_id" hidden>{{$list->id}}</div>
			<div class="user_head">
				<img src="{{$list->headimg}}">
			</div>
			<div class="user_message">
				<div class="user_name">{{$list->username}}</div>
				<button class="btn-delete2 delete_remark">删除</button>
				<div class="user_time">{{$list->created_at}}</div>
				<div class="user_zan">
				@if($list->is_like)
					<img src="{{asset('/image/detail/Fabulouspress@2x.png')}}">
				@else
					<img src="{{asset('/image/detail/Fabulous@2x.png')}}">
				@endif
					<span class="zan_number">{{$list->like}}</span>
				</div>
				<div class="user_comment">{{$list->comment}}</div>
			</div>
		</div>
		@endforeach
	</div>
</div>
<div class="write_remark">
	<input type="text" placeholder="写评论..." class="write_remark_input">
	@if($house->is_like)
	<img src="{{asset('/image/detail/fabulous_icon_fabulous_disabled2@2x.png')}}" class="loop_fabulous">
	@else
	<img src="{{asset('/image/detail/fabulous_icon_fabulous_disabled@2x.png')}}" class="loop_fabulous">
	@endif
	<span class="new_fabulous_num">{{count($house->like_count)}}</span>

	<a href="#comment"><img src="{{asset('/image/detail/comment@2x.png')}}" class="loop_remark">
	<span class="new_remark_num">{{count($house->comments)}}</span></a>
	@if($house->is_collect)
	<img src="{{asset('/image/detail/Collectionpress@2x.png')}}" class="loop_favorite">
	@else
	<img src="{{asset('/image/detail/Collection@2x.png')}}" class="loop_favorite">
	@endif
	<img src="{{asset('/image/detail/share@2x.png')}}" class="loop_share">
</div>
<div class="write_on">
	<textarea class="write_person" placeholder="写评论..." autofocus></textarea>
	<button class="remark_submit">发表</button>
</div>
<div id="layer_delete">
	<div class="delete_title">确认删除该房屋？</div>
	<button class="layer_cancel">取消</button>
	<button class="layer_confirm">确认</button>
</div>
<div id="layer_delete2">
	<div class="delete_title">确认删除该评论？</div>
	<button class="delete_cancel">取消</button>
	<button class="delete_confirm">确认</button>
</div>
<div class="roomfoot">	
	<a href="{{ URL::asset('index')}}"><div><img src="{{asset('/image/main/tabbar_icon_home_default@2x.png')}}"><br><span>首页</span></div></a>
	<a href="{{ URL::asset('publish')}}"><div><img src="{{asset('/image/main/tabbar_icon_publish_default@2x.png')}}"><br><span>发布</span></div></a>
	<a href="{{ URL::asset('myinfo')}}"><div><img src="{{asset('/image/main/tabbar_icon_mine_default@2x.png')}}"><br><span>我的</span></div></a>
</div>	
<?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1261409102).'" width="0" height="0"/>';?>
</body>
</html>