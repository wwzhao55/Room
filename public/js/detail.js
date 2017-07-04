$(document).ready(function(){
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
				jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','previewImage']
			});
		},
		dataType:'jsonp'
	});
	var text,remarkimg;
	if($('.remark_score').html()>2){
		text=$('.good_text_div').html();
		remarkimg="https://shop.dataguiding.com"+$('.good_img_lists1 img').eq(0).attr('src');
	}else{
		text=$('.bad_text_div').html();
		remarkimg="https://shop.dataguiding.com"+$('.bad_img_lists1 img').eq(0).attr('src');
	}
	var houseid=$('.house_id').html();
	wx.ready(function () {
		wx.onMenuShareTimeline({
		    title: '房查查', // 分享标题
		    link: 'https://shop.dataguiding.com/room/auth/detail/'+houseid, // 分享链接
		    imgUrl: remarkimg, // 分享图标
		    success: function () { 
		        // 用户确认分享后执行的回调函数
		    },
		    cancel: function () { 
		        // 用户取消分享后执行的回调函数
		    }
		});
		wx.onMenuShareAppMessage({
		    title: '房查查', // 分享标题
		    desc: text, // 分享描述
		    link: 'https://shop.dataguiding.com/room/auth/detail/'+houseid, // 分享链接
		    imgUrl: remarkimg, // 分享图标
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
	//预览
	var allimgs=new Array();
	$('.roombody .preview').each(function(){
		var url="https://shop.dataguiding.com"+$(this).attr('src');
		allimgs.push(url);
	})
	$('.roombody').on('click','.preview',function(){
		var currenturl="https://shop.dataguiding.com"+$(this).attr('src');
		wx.previewImage({
		    current: currenturl, // 当前显示图片的http链接
		    urls: allimgs // 需要预览的图片http链接列表
		});
	})
	//点赞
	$('.publish_remark').on('click','.user_zan img',function(){
		var obj=$(this);
		$.ajax({
			url: "/room/detail/like-comment",
			data:{
				comment_id:$(this).parents('.user_remark_list').children('.comment_id').html()
			},
			type: "POST",
			dataType:'json',
			success:function(result){
				if(result.status=="success"){
					var like=obj.parents('.user_remark_list').find('.zan_number').html();
					if(result.msg=="点赞成功"){
						obj.attr('src','/room/image/detail/Fabulouspress@2x.png');
						obj.parents('.user_remark_list').find('.zan_number').html(++like);
					}else{
						obj.attr('src','/room/image/detail/Fabulous@2x.png');
						obj.parents('.user_remark_list').find('.zan_number').html(--like);
					}			
				}else{
					$.alert(result.msg);
				}
				
			}
		});
	});
	//写评论
	$('.write_remark_input').on('click',function(event){
		event.stopPropagation();
		var isIOS = (/iphone|ipad/gi).test(navigator.appVersion);
        //if (isIOS) {
        //    $(".roombody").addClass("roombody2");
        //}
		$('.roombody').css('padding-bottom','110px');
		$('.write_remark').css('display','none');
		$('.roomfoot').css('display','none');
		$('.write_on').css('display','block');
		$('.write_person').focus();
		$('.write_person').val('');
		
	});
	$('.write_on').on('click',function(event){
		event.stopPropagation(); 
	})
	$('.remark_submit').on('click',function(event){
		event.stopPropagation(); 
		if($('.write_person').val()==''){
			$.alert("评论内容不能为空");
		}else{
			$.ajax({
				url: "/room/detail/send-comment",
				data:{
					house_id:$('.house_id').html(),
					comment:$('.write_person').val()
				},
				type: "POST",
				dataType:'json',
				success:function(result){
					if(result.status=="success"){
						$.alert(result.msg);
						location.reload();
					}else{
						$.alert(result.msg);
					}
				}
			});
		}
		
	});
	$(document).on('click',function(){
		$('.roombody').css('padding-bottom','0');
		$('.write_remark').css('display','block');
		$('.roomfoot').css('display','block');
		$('.write_on').css('display','none');
		$('#mcover').css('display','none');
		//$(".roombody").removeClass("roombody2");

	});
	//点赞房屋
	$('.loop_fabulous').on('click',function(){
		var obj=$(this);
		$.ajax({
			url: "/room/detail/like-house",
			data:{
				house_id:$('.house_id').html()
			},
			type: "POST",
			dataType:'json',
			success:function(result){
				if(result.status=="success"){
					var fabulous=obj.parents('.write_remark').find('.new_fabulous_num').html();
					if(result.msg=="点赞成功"){
						obj.attr('src','/room/image/detail/fabulous_icon_fabulous_disabled2@2x.png');
						obj.parents('.write_remark').find('.new_fabulous_num').html(++fabulous);
					}else{
						obj.attr('src','/room/image/detail/fabulous_icon_fabulous_disabled@2x.png');
						obj.parents('.write_remark').find('.new_fabulous_num').html(--fabulous);
					}			
				}else{
					$.alert(result.msg);
				}
				
			}
		});
	})
	//点击收藏
	$('.loop_favorite').on('click',function(){
		var obj=$(this);
		$.ajax({
			url: "/room/detail/collect",
			data:{
				house_id:$('.house_id').html()
			},
			type: "POST",
			dataType:'json',
			success:function(result){
				if(result.status=="success"){
					if(result.msg=="收藏成功"){
						obj.attr('src','/room/image/detail/Collectionpress@2x.png');
					}else{
						obj.attr('src','/room/image/detail/Collection@2x.png');
					}			
				}else{
					$.alert(result.msg);
				}
				
			}
		});
	});
	//分享
	$('.loop_share').on('click',function(event){
		$('#mcover').css('display','block');
		event.stopPropagation();
	});
	//判断是否点击了管理
	var url=window.location.href;	
	var flag=url.substr(url.length-1,1);
	if(flag=="m"){
		$('.btn-delete2').css('display','inline-block');
	}else if(flag=="0"){
		$('.btn-delete2').css('display','none');
	}else{
		$('.btn-delete2').css('display','none');
	}
	//删除整个房屋
	$('.delete_house').on('click',function(){
		var index1=layer.open({
			   type: 1,
			   skin: 'layui-layer-demo', //样式类名
			   closeBtn: 0, //不显示关闭按钮
			   anim: 2,
			   title:false,
			   area: ['500px', '220px'],
			   shadeClose: true, //开启遮罩关闭
			   content: $('#layer_delete'),
			});
		$('.layer_confirm').on('click',function(){
			$.ajax({
				url: "/room/admin/delete-house",
				data:{
					house_id:$('.house_id').html()
				},
				type: "POST",
				dataType:'json',
				success:function(result){
					if(result.status=="success"){
						layer.close(index1);
						window.location.href="/room/index/1";			
					}else{
						$.alert(result.msg);
					}
					
				}
			});
		})
		$('.layer_cancel').on('click',function(){
			layer.close(index1);
		})
	})
	//删除某条评论
	$('.delete_remark').on('click',function(){
		var comment_id=$(this).parents('.user_remark_list').children('.comment_id').html();
		var index2=layer.open({
			   type: 1,
			   skin: 'layui-layer-demo', //样式类名
			   closeBtn: 0, //不显示关闭按钮
			   anim: 2,
			   title:false,
			   area: ['500px', '220px'],
			   shadeClose: true, //开启遮罩关闭
			   content: $('#layer_delete2'),
			});
		$('.delete_confirm').on('click',function(){
			$.ajax({
				url: "/room/admin/delete-comment",
				data:{
					house_id:$('.house_id').html(),
					comment_id:comment_id
				},
				type: "POST",
				dataType:'json',
				success:function(result){
					if(result.status=="success"){
						layer.close(index2);
						location.reload();			
					}else{
						$.alert(result.msg);
					}
					
				}
			});
		})
		$('.delete_cancel').on('click',function(){
			layer.close(index2);
		})
	})
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