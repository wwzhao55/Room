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
				jsApiList: ['getLocation', 'chooseImage','previewImage','uploadImage','onMenuShareTimeline','onMenuShareAppMessage']
			});
		},
		dataType:'jsonp'
	});
	var datapost={};

	var latitude;
	var longitude;
	var province;
	var city;
	var district;
	var name;
	var address;
	wx.ready(function(){		
		//获取地理位置接口

		$('#suggestId').on('focus',function(){
			$('.roombody').css('display','none');
			$('.roomfoot').css('display','none');
			$('.area_list').css('display','block');
			wx.getLocation({
			    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
			    success: function (res) {
			        latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
			        longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
			        var speed = res.speed; // 速度，以米/每秒计
			        var accuracy = res.accuracy; // 位置精度
			        if(longitude != "" && latitude != ""){
						var map = new BMap.Map("l-map");            // 创建Map实例
						map.centerAndZoom(new BMap.Point(longitude, latitude), 11);
						var options = {
							onSearchComplete: function(results){
								// 判断状态是否正确
								if (local.getStatus() == BMAP_STATUS_SUCCESS){
									//var s = [];
									for (var i = 0; i < results.getCurrentNumPois(); i ++){
										var div="<div class='local_list'>"+
													"<span class='local_title'>"+results.getPoi(i).title+"</span><br/>"+
													"<span class='local_address'>"+
														"<span class='local_province' style='display:none;'>"+results.getPoi(i).province+"</span>"+
														"<span class='local_city' style='display:none;'>"+results.getPoi(i).city+"</span>"+
														"<span class='local_district' style='display:none;'>"+results.getPoi(i).district+"</span>"+
														"<span class='local_street'>"+results.getPoi(i).address+"</span>"+
													"</span>"+
												"</div>";
										$('#r-result').append(div);
									}					
								}
							}
						};
						var local = new BMap.LocalSearch(map, options);
						local.search("小区");  
						// local.search("大厦");
						$('.area_list').on('click','.local_list',function(){
							//var a=$(this).text();
							name=$(this).children('.local_title').html();
							province=$(this).children('.local_address').children('.local_province').html();
							city=$(this).children('.local_address').children('.local_city').html();
							district=$(this).children('.local_address').children('.local_district').html();
							address=$(this).children('.local_address').children('.local_street').html();
							$('.roombody').css('display','block');
							$('.roomfoot').css('display','block');
							$('.area_list').css('display','none');
							$('#suggestId').val(name+" "+address);
						})
					}					
					$('#suggestId1').on('focus',function(){
							$('#r-result').empty();
							var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
								{"input" : "suggestId1"
							    ,"location" : map
							});
							var myValue;
							ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
								var _value = e.item.value;
								myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
								name=_value.business;
								province=_value.province;
								city=_value.city;
								district=_value.district;
								address=_value.street;								
								$('.roombody').css('display','block');
								$('.roomfoot').css('display','block');
								$('.area_list').css('display','none');
								$('#suggestId').val(myValue);
							});
					});
					
			    }
			});
			

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
				
	})
	
	//获取验证码
	$('.btn_code').on('click',function(){
		var phone=$('.person_phone').val();
		if(phone==''){
			$.alert('请输入手机号码');
		}else if(!(/^1[34578]\d{9}$/.test(phone))){
			$.alert('手机号码有误!');
		}else{
			$.ajax({
				type:'POST',
				url:'/room/publish/send-code',
				dataType:'json',
				data:{
					phone:phone
				},
				success:function(data){
					if(data.status == 'success'){
		                var wait=60;
		                time($(".btn_code"));
		                function time(codebtn){
		                    if(wait == 0){
		                        codebtn.attr("disabled",false);
		                        codebtn.removeClass('btn_code_disabled');
		                        codebtn.html("获取验证码");
		                        wait = 60;
		                    }else{
		                        codebtn.attr("disabled",true);
		                        codebtn.addClass('btn_code_disabled');
		                        codebtn.html(wait+"秒后重新获取");
		                        wait--;
		                        setTimeout(function(){
		                            time(codebtn);
		                        },1000);
		                    }
		                }
		            }else{
		                $(".btn_code").attr("disabled",false);
		            }
				},
				error:function(){
		        	$.alert("网络异常，请稍后刷新重试");
		        }
			});
		}
	});
	//上传主图片
	//var main_img='';
	var s=0,q=0;
	var bad_imgs=new Array();
	var good_imgs=new Array();
	// $('.add_image').on('click',function(){	
	// 	wx.chooseImage({
	// 	    count: 1, // 默认9
	// 	    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
	// 	    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
	// 	    success: function (res) {
	// 	    	$('.img_list').remove();
 //    			$('.add_image').css('display','none');
	// 	        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
	// 	        var img_list="<img src='"+localIds+"' class='img_list' />";
	// 			$('.publish_room_image').append(img_list);
	// 			wx.uploadImage({
	// 				localId: localIds[0], // 需要上传的图片的本地ID，由chooseImage接口获得
	// 				isShowProgressTips: 1, // 默认为1，显示进度提示
	// 				success: function (res) {						
	// 					main_img = res.serverId; // 返回图片的服务器端ID
	// 				}
	// 			});				
	// 	    }
	// 	});
		
 //    }); 
 //    $('.publish_room_image').on('click','.img_list',function(){
 //        $('.add_image').click();
 //    });
    
    //上传吐槽，点赞图片 
    $('.bad_add_image').on('click',function(){
    	if(s==3){
    		$.alert('最多上传3张图片');
    	}else{
    		wx.chooseImage({
			    count: 3-s, // 默认9
			    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
			    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
			    success: function (res) {
			        var localIds_bad = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
			        s=s+localIds_bad.length;
			        for(var j=0;j<localIds_bad.length;j++){
				        var img_list="<img src='"+localIds_bad[j]+"' class='bad_img_list' />";
						$('.bad_img_lists').append(img_list);
						wx.uploadImage({
							localId: localIds_bad[j], // 需要上传的图片的本地ID，由chooseImage接口获得
							isShowProgressTips: 1, // 默认为1，显示进度提示
							success: function (res) {
								localIds_bad = res.serverId; // 返回图片的服务器端ID
								bad_imgs.push(localIds_bad);
							}
						});		
			        }
												
			    }
			});
			
    	}    	
    });
    $('.good_add_image').on('click',function(){
    	if(q==3){
    		$.alert('最多上传3张图片');
    	}else{
    		wx.chooseImage({
			    count: 3-q, // 默认9
			    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
			    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
			    success: function (res) {
			        var localIds_good = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
			        q=q+localIds_good.length;
			        for(var j=0;j<localIds_good.length;j++){
				        var img_list="<img src='"+localIds_good[j]+"' class='good_img_list' />";
						$('.good_img_lists').append(img_list);	
						wx.uploadImage({
							localId: localIds_good[j], // 需要上传的图片的本地ID，由chooseImage接口获得
							isShowProgressTips: 1, // 默认为1，显示进度提示
							success: function (res) {
								localIds_good = res.serverId; // 返回图片的服务器端ID
								good_imgs.push(localIds_good);
							}
						});	
			        }				
			    }
			});
			
    	}    	
    });
    //点评等级
    var score=0;
    $('.star').on('click',function(){
    	$(this).prevAll().attr('src','http://shop.dataguiding.com/room/image/publish/star1@2x.png');
    	$(this).attr('src','http://shop.dataguiding.com/room/image/publish/star1@2x.png');
    	$(this).nextAll().attr('src','http://shop.dataguiding.com/room/image/publish/star@2x.png');
    	score=$(this).prevAll().size()+1;
    })
	//确定发布
	$('.btn_publish').on('click',function(){
		var door=$('.publish_number input').val();
		var phone=$('.person_phone').val();
		var code=$('.person_code').val();
		var rent=$('.person_rent').val();
		var direction=$(".person_direction option:selected").val();
		var area=$(".person_area option:selected").val();
		var type=$(".person_type option:selected").val();
		var good_text=$('.good_text').val();
		var bad_text=$('.bad_text').val();
		if($('#suggestId').val()==''){
			$.alert('请搜索您的小区或大厦、街道名称');
			return false;
		}
		if(door==''){
			$.alert('请输入门牌号');
			return false;
		}		
		if(phone==''){
			$.alert('请输入手机号码');
			return false;
		}else if(!(/^1[34578]\d{9}$/.test(phone))){
			$.alert('手机号码有误!');
			return false;
		}
		
		if(district=='undefined'){
			district='';
		}
		if(rent==''){
			$.alert('请输入租金');
			return false;
		}
		if(direction==0){
			$.alert('请选择房屋朝向');
			return false;
		}else if(area==0){
			$.alert('请选择面积');
			return false;
		}else if(type==0){
			$.alert('请选择户型');
			return false;
		}
		if(bad_imgs.length==0){
			$.alert('吐槽图片至少上传一张');
			return false;
		}else if(good_imgs.length==0){
			$.alert('点赞图片至少上传一张');
			return false;
		}
		if(good_text==''){
			$.alert('请输入点赞文字');
			return false;
		}
		if(bad_text==''){
			$.alert('请输入吐槽文字');
			return false;
		}
		if(score==0){
			$.alert('请点评');
			return false;
		}
		if($('.person_phone').attr('readonly')==false){
			datapost.phone=phone;
			datapost.code=code;
			if(code==''){
				$.alert('请输入验证码');
				return false;
			}
		}
		datapost.name=name;
		datapost.longitude=longitude;
		datapost.latitude=latitude;
		datapost.door=door;
		datapost.province=province;
		datapost.city=city;
		datapost.district=district;
		datapost.address=address;
		datapost.rent=rent;
		datapost.orientation=direction;
		datapost.area=area;
		datapost.type=type;
		datapost.good_text=good_text;
		datapost.bad_text=bad_text;
		datapost.good_img=good_imgs;
		datapost.bad_img=bad_imgs;
		datapost.score=score;
		$.ajax({
			url:'/room/publish/submit',
			type:'POST',
			dataType:'json',
			data:datapost,
			success:function(result){
				if(result.status=="success"){
					$.alert('发布成功');
					window.location.href='/room/index';
				}else{ 
					$.alert(result.msg);
				}
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