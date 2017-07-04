<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController,Illuminate\Http\Request;
use App\Models\Collection,App\Models\House,App\User,App\Models\Comment;
use View,Auth,Response,Validator;

class MyinfoController extends BaseController
{
    public function index(){
    	$headimg = Auth::user()->headimg;
    	$name = Auth::user()->name;
    	$phone = Auth::user()->phone;
    	return View::make('room.myinfo',compact('headimg','name','phone'));
    }

    public  function mycollect(){
    	return View::make('room.mycollect');
    }

    public  function mypublish(){
    	return View::make('room.mypublish');
    }

    public  function mycomment(){
    	return View::make('room.mycomment');	
    }

    public function getMycollect(){
    	$collection = Collection::where('user_id',Auth::user()->id)->orderby('created_at','desc')->pluck('house_id');
    	$result = array();
    	foreach ($collection as $key => $house_id) {
    		$house = House::where('id',$house_id)->select('id','name','rent','score','good_img','bad_img')->withCount('like')->withCount('comment')->first();
            if($house->score >= 3){
                $house->main_img = explode(',', $house->good_img)[0];
            }else{
                $house->main_img = explode(',', $house->bad_img)[0];
            }
    		array_push($result, $house);
    	}
    	return Response::Json(['status'=>'success','msg'=>$result]);
    }

    public function getMypublish(){
    	$result = House::where('user_id',Auth::user()->id)->orderby('created_at','desc')->select('id','name','rent','score','good_img','bad_img')->withCount('like')->withCount('comment')->get();
        foreach ($result as $key => $house) {
            if($house->score >= 3){
                $house->main_img = explode(',', $house->good_img)[0];
            }else{
                $house->main_img = explode(',', $house->bad_img)[0];
            }
        }
    	return Response::Json(['status'=>'success','msg'=>$result]);
    }

     public function getMycomment(){
    	$result = Comment::where('user_id',Auth::user()->id)->orderby('created_at','desc')->get();
    	foreach ($result as $key => $comment) {
    		$comment->house = House::where('id',$comment->house_id)->select('id','name','rent','score','good_img','bad_img')->withCount('like')->withCount('comment')->first();
            if($comment->house->score >= 3){
                $comment->house->main_img = explode(',', $comment->house->good_img)[0];
            }else{
                $comment->house->main_img = explode(',', $comment->house->bad_img)[0];
            }
    	}
    	return Response::Json(['status'=>'success','msg'=>$result]);
    }

    public function changePhone(Request $request){
        $validator = Validator::make($request->all(), [
                'phone'=>'required',//加手机号码验证
        ]);
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return Response::Json(['status'=>'fail','msg'=>$show_warning]);
        }
        $user = User::find(Auth::user()->id);
        $user->phone = $request->phone;
        $result = $user->save();
        if($result){
            return Response::Json(['status'=>'success','msg'=>'修改成功']);
        }else{
            return Response::Json(['status'=>'fail','msg'=>'修改失败']);
        }
    }
}
