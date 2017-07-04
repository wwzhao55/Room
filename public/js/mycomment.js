$(document).ready(function(){
	//初始化收藏列表
	function Init(){
		$.ajax({
			url: "/room/myinfo/comment-list",
			type: "GET",
			dataType:'json',
			success:function(result){
				if(result.status=='success'){
					var i=0;
					if(result.msg.length==0){
						var tip="<div class='zero_tip'>暂时没有评论~</div>";
						$('.roombody').append(tip);
					}
					for(i=0;i<result.msg.length;i++){
						var roomlists="<div class='roomlists'></div>";
						var house_id="<div class='house_id' hidden>"+result.msg[i].house.id+"</div>";
						var room_image="<div class='div_img'><img src='/room"+result.msg[i].house.main_img+"' class='room_image'></div>";
						var room_message="<div class='room_message'></div>";
						var room_area="<div class='room_area'>"+result.msg[i].house.name+"</div>";
						var room_level="<div class='room_level'></div>";
						var room_comment="<div class='room_comment'></div>";
						var remark_zan="<img src='/room/image/main/fabulous_icon_fabulous_disabled@2x.png' class='remark_zan'>";
						var zan_num="<span>"+result.msg[i].house.like_count+"</span>";
						var remark_talk="<img src='/room/image/main/comment_icon_comment_disabled@2x.png' class='remark_talk'>";
						var talk_num="<span>"+result.msg[i].house.comment_count+"</span>";
						var img1="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
						var img2,img3,img4,img5;
						if(result.msg[i].house.score==1){
							img2="<img src='/room/image/main/score_icon_score_default@2x.png'>";
							img3="<img src='/room/image/main/score_icon_score_default@2x.png'>";
							img4="<img src='/room/image/main/score_icon_score_default@2x.png'>";
							img5="<img src='/room/image/main/score_icon_score_default@2x.png'>";
						}else if(result.msg[i].house.score==2){
							img2="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
							img3="<img src='/room/image/main/score_icon_score_default@2x.png'>";
							img4="<img src='/room/image/main/score_icon_score_default@2x.png'>";
							img5="<img src='/room/image/main/score_icon_score_default@2x.png'>";
						}else if(result.msg[i].house.score==3){
							img2="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
							img3="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
							img4="<img src='/room/image/main/score_icon_score_default@2x.png'>";
							img5="<img src='/room/image/main/score_icon_score_default@2x.png'>";
						}else if(result.msg[i].house.score==4){
							img2="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
							img3="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
							img4="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
							img5="<img src='/room/image/main/score_icon_score_default@2x.png'>";
						}else if(result.msg[i].house.score==5){
							img2="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
							img3="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
							img4="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
							img5="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
						}
						var score="<span class='score'>"+result.msg[i].house.score+"分</span>";
						var room_value="<span class='room_value'>￥"+result.msg[i].house.rent+"元/月</span>";
						$('.roombody').append(roomlists);
						$('.roomlists').eq(i).append(house_id).append(room_image).append(room_message);
						$('.room_message').eq(i).append(room_area).append(room_level).append(room_comment);
						$('.room_level').eq(i).append(img1).append(img2).append(img3).append(img4).append(img5).append(score).append(room_value);
						$('.room_comment').eq(i).append(remark_zan).append(zan_num).append(remark_talk).append(talk_num);						
					}
				}else{
					$.alert(result.msg);
				}
				
			}
		});
	}
	Init();
	//进入详情
	$('.roombody').on('click','.roomlists',function(){
		var id=$(this).children('.house_id').html();
		window.location.href="/room/detail/"+id;
	});
	//ajax设置csrf_token
	$.ajaxSetup({
          headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
    });
	$.ajax({
		url: 'https://shop.dataguiding.com/wxapi/netBankRequestOne.action?url='+encodeURIComponent(window.location.href),
		success: function(data) {
			wx.config({
				debug: false,
				appId: data.appId,
				timestamp: data.timestamp,
				nonceStr: data.nonceStr,
				signature: data.signature,
				jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage']
			});
		},
		dataType:'jsonp'
	});
	wx.ready(function () {
		wx.onMenuShareTimeline({
		    title: '房查查', // 分享标题
		    link: 'https://shop.dataguiding.com/room', // 分享链接
		    imgUrl: 'https://shop.dataguiding.com/room/image/ICON.jpg', // 分享图标
		    success: function () { 
		        // 用户确认分享后执行的回调函数
		    },
		    cancel: function () { 
		        // 用户取消分享后执行的回调函数
		    }
		});
		wx.onMenuShareAppMessage({
		    title: '房查查', // 分享标题
		    desc: '快来房查查找房子吧', // 分享描述
		    link: 'https://shop.dataguiding.com/room', // 分享链接
		    imgUrl: 'https://shop.dataguiding.com/room/image/ICON.jpg', // 分享图标
		    type: '', // 分享类型,music、video或link，不填默认为link
		    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
		    success: function () { 
		        // 用户确认分享后执行的回调函数
		    },
		    cancel: function () { 
		        // 用户取消分享后执行的回调函数
		    }
		});
	});
	//判断手机横竖屏状态：  
    window.addEventListener("onorientationchange" in window ? "orientationchange" : "resize", function() {  
            if (window.orientation === 180 || window.orientation === 0) {   
               //alert('竖屏状态！');  
            }   
            if (window.orientation === 90 || window.orientation === -90 ){   
                $.alert('请不要横屏');  
            }    
    }, false); 
});