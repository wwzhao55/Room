<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\User;
use Redirect,Auth;

class OauthController extends BaseController
{
    public function index(Request $request){
    	$code = $request->code;
    	$oauth_url = 'http://appwxchat.yiguanjinrong.yg/wxChat/netBankRequestOne.action?wxCode='.$code;
    	$result_raw = $this->getJson(file_get_contents($oauth_url));
    	$result = json_decode($result_raw);

    	if(isset($result->errcode)){
    		return $result->errmsg;
    	}else{
    		//access_token获取信息
    		$info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$result->access_token.'&openid='.$result->openid.'&lang=zh_CN';
    		$info = json_decode(file_get_contents($info_url));

    		if(isset($info->errcode)){
    			return $info->errmsg;
    		}else{
    			if(User::where('openid',$info->openid)->count()==0){
    				//openid不存在，记录用户
    				$userdata = array(
    						'openid' => $info->openid,
    						'name' => $info->nickname,
    						'sex' => $info->sex,
    						'province' => $info->province,
    						'city' => $info->city,
    						'headimg' => $info->headimgurl,
    					);
    				$loginuser = new User;
    				$loginuser->fill($userdata)->save();
    			}else{
    				$loginuser = User::where('openid',$info->openid)->first();
    			}
    			//登录
    			Auth::loginUsingId($loginuser->id);
                if($request->state){
                    return redirect('detail/'.$request->state);
                }
    			return redirect('index');
    		}
    	}	
    }

    public function getJson($str)
	{
	  $result = array();
	  preg_match("/(?:\()(.*)(?:\))/i",$str, $result);
	  return $result[1];
	}
}
