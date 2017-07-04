<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController,Illuminate\Http\Request;
use App\Models\House,App\Models\Comment,App\Models\LikeComment,App\Models\LikeHouse,App\Models\Collection,App\Models\HouseArea,App\Models\HouseType;
use View,Redirect,Response,Auth,Validator;

class DetailController extends BaseController
{
    public function index($id=0){
    	$house = House::find($id);
    	if($house){
    		$house->good_img = explode(',', $house->good_img);
    		$house->bad_img = explode(',', $house->bad_img);
    		$house->area = $house->getArea->area;
    		$house->type = $house->getType->type; 
    		$house->like_count = LikeHouse::where('house_id',$id)->count();
    		$house->is_like = LikeHouse::where('house_id',$id)->where('user_id',Auth::user()->id)->count();
    		$house->comments = Comment::where('house_id',$id)->orderby('created_at','desc')->get();
    		$house->is_collect = Collection::where('user_id',Auth::user()->id)->where('house_id',$id)->count();
    		foreach ($house->comments as $key => $comment) {
    			$comment->username = $comment->user->name;
    			$comment->headimg = $comment->user->headimg;
    			$comment->is_like = LikeComment::where('user_id',Auth::user()->id)->where('comment_id',$comment->id)->count();//是否点赞该评论
    		}
    	}else{
    		return Redirect::to('index');
    	}
    	//var_dump($house->toJson());
    	$area = HouseArea::where('status',1)->select('id','area')->get();
    	$type = HouseType::where('status',1)->select('id','type')->get();
    	return View::make('room.detail',compact('house','area','type'));
    }

    public function sendComment(Request $request){
    	$validator = Validator::make($request->all(), [
           	 	'house_id'=>'required|exists:houses,id',
           	 	'comment'=>"required",
	]);
	if ($validator->fails()) {
		$warnings = $validator->messages();
		$show_warning = $warnings->first();
		return Response::Json(['status'=>'fail','msg'=>$show_warning]);
	}
	$data = $request->all();
	$data['user_id'] = Auth::user()->id;
	$data['like'] = 0;
	$result = Comment::create($data);
	if($result){
		return Response::Json(['status'=>'success','msg'=>'发布成功']);
	}else{
		return Response::Json(['status'=>'fail','msg'=>'发布失败']);
	}
    }

    public function likeComment(Request $request){
    	$validator = Validator::make($request->all(), [
           	 	'comment_id'=>'required|exists:comments,id',
	]);
	if ($validator->fails()) {
		$warnings = $validator->messages();
		$show_warning = $warnings->first();
		return Response::Json(['status'=>'fail','msg'=>$show_warning]);
	}
	$comment = LikeComment::where('comment_id',$request->comment_id)->where('user_id',Auth::user()->id)->first();
	if($comment){
		//取消点赞
		$comment->delete();
		$like = Comment::find($request->comment_id);
		$like->like = $like->like ? $like->like-1 : 0;
		$like->save();
		return Response::Json(['status'=>'success','msg'=>'取消点赞成功']); 
	}else{
		//点赞
		LikeComment::create(['user_id'=>Auth::user()->id,'comment_id'=>$request->comment_id]);
		$like = Comment::find($request->comment_id);
		$like->like += 1;
		$like->save();
		return Response::Json(['status'=>'success','msg'=>'点赞成功']);
	}
	
    }

    public function collectHouse(Request $request){
    	$validator = Validator::make($request->all(), [
           	 	'house_id'=>'required|exists:houses,id',
		]);
		if ($validator->fails()) {
			$warnings = $validator->messages();
			$show_warning = $warnings->first();
			return Response::Json(['status'=>'fail','msg'=>$show_warning]);
		}
		$collect = Collection::where('user_id',Auth::user()->id)->where('house_id',$request->house_id)->first();
		if($collect){
			//取消收藏
			$result = $collect->delete();
			if($result){
				return Response::Json(['status'=>'success','msg'=>'取消收藏成功']);
			}else{
				return Response::Json(['status'=>'fail','msg'=>'取消收藏失败']);
			}
		}else{
			//收藏
			$result = Collection::create(['user_id'=>Auth::user()->id,'house_id'=>$request->house_id]);
			if($result){
				return Response::Json(['status'=>'success','msg'=>'收藏成功']);
			}else{
				return Response::Json(['status'=>'fail','msg'=>'收藏失败']);
			}
		}
    }

    public function likeHouse(Request $request){
    	$validator = Validator::make($request->all(), [
           	 	'house_id'=>'required|exists:houses,id',
		]);
		if ($validator->fails()) {
			$warnings = $validator->messages();
			$show_warning = $warnings->first();
			return Response::Json(['status'=>'fail','msg'=>$show_warning]);
		}
		$like = LikeHouse::where('user_id',Auth::user()->id)->where('house_id',$request->house_id)->first();
		if($like){
			//取消点赞
			$result = $like->delete();
			if($result){
				return Response::Json(['status'=>'success','msg'=>'取消点赞成功']);
			}else{
				return Response::Json(['status'=>'fail','msg'=>'取消点赞失败']);
			}
		}else{
			//点赞
			$result = LikeHouse::create(['user_id'=>Auth::user()->id,'house_id'=>$request->house_id]);
			if($result){
				return Response::Json(['status'=>'success','msg'=>'点赞成功']);
			}else{
				return Response::Json(['status'=>'fail','msg'=>'点赞失败']);
			}
		}
    }
}
