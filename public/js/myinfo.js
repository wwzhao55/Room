$(document).ready(function(){
	var index;
	//修改手机号码
	$('.modify_number').on('click',function(){
			$('.layer_input').val('');
			$('.no_tip').css('display','none');
			index=layer.open({
			   type: 1,
			   skin: 'layui-layer-demo', //样式类名
			   closeBtn: 0, //不显示关闭按钮
			   anim: 2,
			   title:false,
			   area: ['500px', '220px'],
			   shadeClose: true, //开启遮罩关闭
			   content: $('#layer_modify'),
			});
	});
	$('.layer_cancel').on('click',function(){
		layer.close(index);
	});
	$('.layer_confirm').on('click',function(){
		var new_phone=$('.layer_input').val();
		if(new_phone==''){
			$('.no_tip').css('display','block');
		}else{
			$.ajax({
				url: "/room/myinfo/change-phone",
				data:{
					phone:new_phone
				},
				type: "POST",
				dataType:'json',
				success:function(result){
					if(result.status=='success'){
						layer.close(index);
						$('.vip_message .phone_number').html(new_phone);
						$.alert('修改成功');
					}else{
						$.alert(result.msg);
					}
				}
			});
				
		}
	})
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
	//管理
	// $('.roombody').on('click','.manage',function(){
	// 	$(this).css('display','none');
	// 	$('.finish_manage').css('display','block');
	// 	window.location.href="/room/index/1";
	// })
	// $('.roombody').on('click','.finish_manage',function(){
	// 	$(this).css('display','none');
	// 	$('.manage').css('display','block');
	// })
	//判断手机横竖屏状态：  
    window.addEventListener("onorientationchange" in window ? "orientationchange" : "resize", function() {  
            if (window.orientation === 180 || window.orientation === 0) {   
               //alert('竖屏状态！');  
            }   
            if (window.orientation === 90 || window.orientation === -90 ){   
                $.alert('请不要横屏');  
            }    
    }, false); 
})