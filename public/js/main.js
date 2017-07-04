$(document).ready(function(){
	var datapost={};
	var order=0;
	var is_district=0;
	var latitude;
	var longitude;
	$('.select_type>div').on('click',function(){
		$('.roombody').empty();
		$('.select_type>div').removeClass('onactive');
		$(this).addClass('onactive');
		if($(this).html()=='推荐'){
			order=0;
		}else if($(this).html()=='热点'){
			order=1;
		}
		else if($(this).html()=='附近'){
			order=2;
			datapost.longitude=longitude;
			datapost.latitude=latitude;
		}
		Init();
	});
	// 初始化获取房屋列表
	function Init(){
		$('.dropload-down').remove();
		var page=1;		
				var dropload=$('body').dropload({
				    scrollArea : window,
				    domDown:{
				    	domClass : 'dropload-down',
				        domRefresh : '<div class="dropload-refresh">↑上拉加载更多</div>',
				        domLoad : '<div class="dropload-load"><span class="loading"></span>加载中...</div>',
				        domNoData : '<div class="dropload-noData">没有更多数据了</div>'
				    },
				    loadDownFn : function(me){
				    	setTimeout(function(){
				    		datapost.order=order;
							datapost.is_district=is_district;
							datapost.page=page;
							datapost.place=$('.search_input').val();
							datapost.district=$('.current_area').html();
							if(page==1){
								$('.roombody').empty();
							}
							$.ajax({
								url: "/room/house-list",
								data:datapost,
								type: "GET",
								dataType:'json',
								success:function(result){
									
									if(result.status=='success'){
										if(result.msg.data.length==0){										
											// 锁定
									        me.lock();
									        // 无数据
									        me.noData();
									        // return false;
									        $('.dropload-down').html('<div class="dropload-noData">没有更多数据了</div>');
										}else{
											var i=0;
											var length;
											if(page==result.msg.last_page){
												length=result.msg.total%10;
											}else{
												length=10;
											}	
											//console.log(length);				
											for(i=0;i<length;i++){
												var roomlists="<div class='roomlists'></div>";
												var house_id="<div class='house_id' hidden>"+result.msg.data[i].id+"</div>";
												var room_image="<div class='div_img'><img src='/room"+result.msg.data[i].main_img+"' class='room_image'></div>";
												var room_message="<div class='room_message'></div>";
												var room_area="<span class='room_area'>"+result.msg.data[i].name+"</span>";
												var room_level="<div class='room_level'></div>";
												var room_comment="<div class='room_comment'></div>";
												var remark_zan="<img src='/room/image/main/fabulous_icon_fabulous_disabled@2x.png' class='remark_zan'>";
												var zan_num="<span>"+result.msg.data[i].like_count+"</span>";
												var remark_talk="<img src='/room/image/main/comment_icon_comment_disabled@2x.png' class='remark_talk'>";
												var talk_num="<span>"+result.msg.data[i].comment_count+"</span>";
												var img1="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
												var img2,img3,img4,img5;
												var btndelete="<button class='btn-delete delete_house'>删除</button>";
												if(result.msg.data[i].score==1){
													img2="<img src='/room/image/main/score_icon_score_default@2x.png'>";
													img3="<img src='/room/image/main/score_icon_score_default@2x.png'>";
													img4="<img src='/room/image/main/score_icon_score_default@2x.png'>";
													img5="<img src='/room/image/main/score_icon_score_default@2x.png'>";
												}else if(result.msg.data[i].score==2){
													img2="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
													img3="<img src='/room/image/main/score_icon_score_default@2x.png'>";
													img4="<img src='/room/image/main/score_icon_score_default@2x.png'>";
													img5="<img src='/room/image/main/score_icon_score_default@2x.png'>";
												}else if(result.msg.data[i].score==3){
													img2="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
													img3="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
													img4="<img src='/room/image/main/score_icon_score_default@2x.png'>";
													img5="<img src='/room/image/main/score_icon_score_default@2x.png'>";
												}else if(result.msg.data[i].score==4){
													img2="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
													img3="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
													img4="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
													img5="<img src='/room/image/main/score_icon_score_default@2x.png'>";
												}else if(result.msg.data[i].score==5){
													img2="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
													img3="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
													img4="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
													img5="<img src='/room/image/main/score_icon_score_pressed@2x.png'>";
												}
												var score="<span class='score'>"+result.msg.data[i].score+"分</span>";
												var room_value="<span class='room_value'>￥"+result.msg.data[i].rent+"元/月</span>";
												$('.roombody').append(roomlists);
												$('.roomlists').eq(10*(page-1)+i).append(house_id).append(room_image).append(room_message);
												$('.room_message').eq(10*(page-1)+i).append(room_area).append(btndelete).append(room_level).append(room_comment);
												$('.room_level').eq(10*(page-1)+i).append(img1).append(img2).append(img3).append(img4).append(img5).append(score).append(room_value);
												$('.room_comment').eq(10*(page-1)+i).append(remark_zan).append(zan_num).append(remark_talk).append(talk_num);							
											}
								            
								            me.resetload();
								            page++;	
										}
										//判断是否点击了管理
										var url=window.location.href;
										var flag=url.substr(url.length-1,1);
										if(flag=="1"){
											$('.roombody').find('.btn-delete').css('display','inline-block');
											$('.roombody').on('click','.roomlists',function(){
												event.stopPropagation(); 
												var id=$(this).children('.house_id').html();
												window.location.href="/room/detail/"+id+"/m";
											});
										}else if(flag=="0"){
											$('.roombody').find('.btn-delete').css('display','none');
											$('.roombody').on('click','.roomlists',function(){
												event.stopPropagation(); 
												var id=$(this).children('.house_id').html();
												window.location.href="/room/detail/"+id+"/0";
											});
										}else{
											$('.btn-delete').css('display','none');
											$('.roombody').on('click','.roomlists',function(){
												event.stopPropagation(); 
												var id=$(this).children('.house_id').html();
												window.location.href="/room/detail/"+id;
											});
										}
									}else{
										$.alert(result.msg);
									}
								},
								error: function(xhr, type){
							        console.log('Ajax error!');
							        // 即使加载出错，也得重置
							        me.resetload();
							    }
							});             
						},1000);
				        	
				    },
				    threshold : 50
				});
	}
	//搜索
	$('.search_input').bind('keypress',function(e){
        if (e.keyCode == '13') {
            if ($('.search_input').val() == '') {
                $.alert('请您想住的区域或小区');
            }else{
            	$('.roombody').empty();
            	Init();
            } 
        }  
	});
	//选择地区
	var index;
	$('.current_area').on('click',function(){
			$('.layer_input1').val('');
			$('.layer_input2').val('');
			index=layer.open({
			   type: 1,
			   skin: 'layui-layer-demo', //样式类名
			   closeBtn: 0, //不显示关闭按钮
			   anim: 2,
			   title:false,
			   area: ['500px', '300px'],
			   shadeClose: true, //开启遮罩关闭
			   content: $('#layer_address'),
			});
	})
	$('.address_city').on('click',function(){
		$('#layer_address').find('.btn_pressed').removeClass('btn_pressed');
		$(this).addClass('btn_pressed');
		$('.layer_input1').css('display','block');
		$('.layer_input2').css('display','none');
		$('.layer_input2').val('');
	})
	$('.address_district').on('click',function(){
		$('#layer_address').find('.btn_pressed').removeClass('btn_pressed');
		$(this).addClass('btn_pressed');
		$('.layer_input1').css('display','none');
		$('.layer_input2').css('display','block');
		$('.layer_input1').val('');
	})
	$(".layer_input1").cityPicker({
	    title: "请选择城市",
	    showDistrict: false
	});
	$(".layer_input2").cityPicker({
	    title: "请选择地区"
	});
	$('.layer_cancel').on('click',function(){
		layer.close(index);
	});
	$('.layer_confirm').on('click',function(){
		if($('.layer_input1').val()){
			var m=$('.layer_input1').val().split(" ");
			$('.current_area').html(m[m.length-1]);
			is_district=0;
			layer.close(index);
			$('.roombody').empty();
			Init();
		}else if($('.layer_input2').val()){
			var m=$('.layer_input2').val().split(" ");
			$('.current_area').html(m[m.length-1]);
			is_district=1;
			layer.close(index);
			$('.roombody').empty();
			Init();
		}
	})
	$('.down_icon').on('click',function(){
		$('.current_area').click();
	})
	//进入详情
	// $('.roombody').on('click','.roomlists',function(){
	// 	var id=$(this).children('.house_id').html();
	// 	window.location.href="/room/detail/"+id;
	// });
	//定位
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
				jsApiList: ['getLocation', 'onMenuShareTimeline','onMenuShareAppMessage']
			});
		},
		dataType:'jsonp'
	});
	
	
			wx.ready(function () {
				wx.getLocation({
					type: 'wgs84',
					success: function (res) {
						latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
						longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
						var speed = res.speed; // 速度，以米/每秒计
						var accuracy = res.accuracy; // 位置精度
						var point = new BMap.Point(longitude,latitude);
						var geoc = new BMap.Geocoder();    
						geoc.getLocation(point, function(rs){  
					        var addComp = rs.addressComponents;  
					        if(addComp.district==""){
					        	is_district=0;
								$('.current_area').html(addComp.city);
							}else{
								is_district=1;
								$('.current_area').html(addComp.district);
							}
							Init();
					    });  
						
					},
					cancel: function () {
						//这个地方是用户拒绝获取地理位置
						//默认经纬度
						district = "朝阳区";
						$('.current_area').html(district);
					}
				
				});
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
	
	//删除整个房屋
	$('.roombody').on('click','.delete_house',function(event){
		event.stopPropagation(); 
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
		var house_id=$(this).parents('.roomlists').children('.house_id').html();
		$('.delete_cancel').on('click',function(event){
			event.stopPropagation();
			layer.close(index1);
		})
		$('.delete_confirm').on('click',function(event){
			event.stopPropagation();
			$.ajax({
				url: "/room/admin/delete-house",
				data:{
					house_id:house_id
				},
				type: "POST",
				dataType:'json',
				success:function(result){
					if(result.status=="success"){
						layer.close(index1);
						location.reload();			
					}else{
						$.alert(result.msg);
					}
					
				}
			});
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
  
})