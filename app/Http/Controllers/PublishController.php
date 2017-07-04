<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request,App\Api\Api;
use App\Models\HouseArea,App\Models\HouseType,App\Models\House,App\User;
use View,Validator,Session,Response,Auth,Cache,Carbon\Carbon,Message;
require app_path('Api/WechatImg.php');
class PublishController extends BaseController
{
    public function index(){
    	$area = HouseArea::where('status',1)->select('id','area')->get();
    	$type = HouseType::where('status',1)->select('id','type')->get();

    	return View::make('room.publish',array('phone'=>Auth::user()->phone,'area'=>$area,'type'=>$type));
    }

    public function submit(Request $request){
	$validator = Validator::make($request->all(), [
		'name'=>'required|max:100',
		'door'=>'max:20',
		'province'=>'max:100',
		'city'=>'max:100',
		'district'=>'max:100',
		//'address'=>'required',
		'longitude'=>array('required','regex:/^[-]?(\d|([1-9]\d)|(1[0-7]\d)|(180))(\.\d*)?$/'),
   	 	'latitude'=>array('required','regex:/^[-]?(\d|([1-8]\d)|(90))(\.\d*)?$/'),
   	 	'phone'=>array('regex:/^1(3[0-9]4[57]5[0-35-9]8[0-9]70)\d{8}$/'),//后期添加手机号码验证
   	 	//'code'=>'required',
   	 	'rent'=>'required|numeric',
   	 	'area'=>'required|exists:house_area,id',
   	 	'type'=>'required|exists:house_type,id',
   	 	'orientation'=>'required',
   	 	//'main_img'=>'required',
   	 	'good_text'=>'required',
   	 	'good_img'=>'required',//后期加图片数组验证
   	 	'bad_text'=>'required',
   	 	'bad_img'=>'required',//后期加图片数组验证
   	 	'score'=>'required|numeric'
	]);
	if ($validator->fails()) {
		$warnings = $validator->messages();
		$show_warning = $warnings->first();
		return Response::Json(['status'=>'fail','msg'=>$show_warning]);
	}
	//检查验证码
	if($request->has('phone')){
		if(!$request->has('code')){
			return Response::Json(['status'=>'fail','msg'=>'请先获取验证码']);
		}
		if($request->code != Cache::get($request->phone)['code']){
			return Response::Json(['status'=>'fail','msg'=>'验证码不正确']);
		}
	}
	
	//上传图片
	//存数据
	$pubdata = $request->except('phone','code','good_img','bad_img');

	//微信服务器下载图片
	// $main_img = $this->DownloadFromWechat($request->main_img,'main_img');
	// if($main_img['status'] == 'success'){
	// 	$pubdata['main_img'] = $main_img['message'];
	// }else{
	// 	return Response::Json(['status'=>'success','msg'=>'主图上传失败']);
	// }

		//$bad_mediaId = explode(',', $request->bad_img);
	$bad_mediaId = $request->bad_img;
	$bad_arr = array();
	foreach ($bad_mediaId as $key => $id) {
		$bad_img = $this->DownloadFromWechat($id,'bad_img');
		if($bad_img['status'] == 'success'){
			array_push($bad_arr,$bad_img['message']);
		}else{
			return Response::Json(['status'=>'success','msg'=>'吐槽图片上传失败']);
		}
	}
	$pubdata['bad_img'] = implode(',', $bad_arr);

		//$good_mediaId = explode(',', $request->good_img);
	$good_mediaId = $request->good_img;
	$good_arr = array();
	foreach ($good_mediaId as $key => $id) {
		$good_img = $this->DownloadFromWechat($id,'good_img');
		if($good_img['status'] == 'success'){
			array_push($good_arr,$good_img['message']);
		}else{
			return Response::Json(['status'=>'success','msg'=>'吐槽图片上传失败']);
		}
	}
	$pubdata['good_img'] = implode(',', $good_arr);


	$pubdata['user_id'] = Auth::user()->id;
	//地址信息完善
	$full_addr_url = 'http://api.map.baidu.com/geocoder/v2/?location='.$request->latitude.','.$request->longitude.'&output=json&pois=0&ak=L2BW0fF1IGns9FHiHMafrG2YGtyI7LmM';
	$full_addr = httpGet($full_addr_url);
	$full_addr = json_decode($full_addr);
	if($full_addr->status == 0){
		if($request->province==""){
			$pubdata['province'] = $full_addr->result->addressComponent->province;
		}
		if($request->city==""){
			$pubdata['city'] = $full_addr->result->addressComponent->city;
		}
		if($request->district==""){
			$pubdata['district'] = $full_addr->result->addressComponent->district;
		}
	}

	$house = new House;
	$result = $house->fill($pubdata)->save();
	//存储电话
	if($request->has('phone')){
		$user = User::find(Auth::user()->id);
		$user->phone = $request->phone;
		$user->save();
	}
	
	if($result){
		return Response::Json(['status'=>'success','msg'=>'发布成功']);
	}else{
		return Response::Json(['status'=>'fail','msg'=>'发布失败']);
	}
    }

    public function sendcode(Request $request){
    	$validator = Validator::make($request->all(), [
           	 	'phone'=>array('required'),//后期添加手机号码验证
	]);
	if ($validator->fails()) {
		$warnings = $validator->messages();
		$show_warning = $warnings->first();
		return Response::Json(['status'=>'fail','msg'=>$show_warning]);
	}
	$code = $this->generate_code();
	//发送短信

	$res = Message::sendCode($request->phone,$code);
        	if(strstr($res,'success')){
        		$expiresAt = Carbon::now()->addMinutes(10);
        		Cache::put($request->phone,['code'=>$code], $expiresAt);
        		return Response::Json(['status'=>'success','msg'=>'发送成功','code'=>$code]);
        	}else{
        		return Response::json(['status' => 'error','msg' => '服务器忙，请稍后再试','phone' => $phone]);
        	}
	
    }

    public function generate_code($length = 6) {
	    return rand(pow(10,($length-1)), pow(10,$length)-1);
    }

    public function addArea(Request $request){
    	$validator = Validator::make($request->all(), [
           	 	'area'=>'required',
	]);
	if ($validator->fails()) {
		$warnings = $validator->messages();
		$show_warning = $warnings->first();
		return Response::Json(['status'=>'fail','msg'=>$show_warning]);
	}
	$result = HouseArea::create(['area'=>$request->area]);
	if($result){
		return Response::Json(['status'=>'success','msg'=>'添加成功']);
	}else{
		return Response::Json(['status'=>'fail','msg'=>'添加失败']);
	}
    }

    public function addType(Request $request){
    	$validator = Validator::make($request->all(), [
           	 	'type'=>'required',
	]);
	if ($validator->fails()) {
		$warnings = $validator->messages();
		$show_warning = $warnings->first();
		return Response::Json(['status'=>'fail','msg'=>$show_warning]);
	}
	$result = HouseType::create(['type'=>$request->type]);
	if($result){
		return Response::Json(['status'=>'success','msg'=>'添加成功']);
	}else{
		return Response::Json(['status'=>'fail','msg'=>'添加失败']);
	}
    }

    public function DownloadFromWechat($serveId,$path){
    	$json_access_token_info = httpGet('wxapi.dgdev.cn/getAccessToken.php');
	$array_access_token_info = json_decode($json_access_token_info);
	if($array_access_token_info->status == 'success'){
		$access_token = $array_access_token_info->access_token;
	}else{
		return array(
	 		'status'=>'fail',
	 		'message'=>'图片上传失败！',
	 		);
	}
	
	//$down_url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=_Q9ET7CS3931-2QqSOp1Z6WblWMpllCl63vRuTLCHi_ZWE7mC2Q3UyfiCsW11DR0";
	$down_url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$serveId;
	$front_img_info = downloadWeixinFile($down_url);
	if(!checkDownload($front_img_info) ){
		return array(
	 		'status'=>'fail',
	 		'message'=>'图片上传失败！',
	 		);
	}

	$front_img_name =md5(time().rand(0,10000)).'.jpg';
	$result1 = saveWeixinFile('uploads/'.$path,$front_img_name,$front_img_info['body']);

	if($result1){
		return  array(
	 		'status'=>'success',
	 		'message'=>'/uploads/'.$path.'/'.$front_img_name,
	 		);
	}else{
		return array(
	 		'status'=>'fail',
	 		'message'=>'图片上传失败！',
	 		);
	}
	
    }

     private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

}
