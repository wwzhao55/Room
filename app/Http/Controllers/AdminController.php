<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request,App\Models\House,App\Models\LikeHouse,App\Models\LikeComment,App\Models\Comment,App\Models\Collection;
use Response,Validator,Auth,DB;

class AdminController extends BaseController
{
	public function deleteHouse(Request $request){
		if(Auth::user()->is_admin!=1){
			return Response::Json(['status'=>'fail','msg'=>'权限不足']);
		}
		$validator = Validator::make($request->all(), [
            'house_id'=>'required|exists:houses,id'
        ]);
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return Response::Json(['status'=>'fail','msg'=>$show_warning]);
        }
        //删除房屋（收藏、评论、点赞、图片）
        $house_id = $request->house_id;
        DB::beginTransaction();
        try{
        	Collection::where('house_id',$house_id)->delete();
            $all_comments = array_flatten(Comment::where('house_id',$house_id)->select('id')->get()->toArray());
            LikeComment::whereIn('comment_id',$all_comments)->delete();
        	Comment::where('house_id',$house_id)->delete();
        	LikeHouse::where('house_id',$house_id)->delete();
        	$house = House::find($house_id);
        	$good_imgs = explode(',',$house->good_img);
        	$bad_imgs = explode(',',$house->bad_img);
        	$house->delete();
        	DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return array('status'=>"fail",'msg'=>'删除失败');
        }
        //删除图片
        foreach ($good_imgs as $key => $trash) {
        	if(file_exists(public_path($trash)))
		        if(is_file(public_path($trash))){
		            unlink(public_path($trash));
		        }
        }
        foreach ($bad_imgs as $key => $trash) {
        	if(file_exists(public_path($trash)))
		        if(is_file(public_path($trash))){
		            unlink(public_path($trash));
		        }
        }
        return array('status'=>"success",'msg'=>'删除成功');
	}

	public function deleteComment(Request $request){
		if(Auth::user()->is_admin!=1){
			return Response::Json(['status'=>'fail','msg'=>'权限不足']);
		}
		$validator = Validator::make($request->all(), [
                'house_id'=>'required|exists:houses,id',
                'comment_id'=>"required"
        ]);
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return Response::Json(['status'=>'fail','msg'=>$show_warning]);
        }
        $house_id = $request->house_id;
        if($request->comment_id == 0){
        	//删除所有评论
        	DB::beginTransaction();
	        try{
	        	$all_comments = array_flatten(Comment::where('house_id',$house_id)->select('id')->get()->toArray());
	        	LikeComment::whereIn('comment_id',$all_comments)->delete();
	        	Comment::where('house_id',$house_id)->delete();
	        	DB::commit();
	        }catch(Exception $e){
	            DB::rollback();
	            return array('status'=>"fail",'msg'=>'删除失败');
	        }
        }else{
        	//删除单个评论
        	$comment = Comment::find($request->comment_id);
        	if($comment){
        		if($comment->house_id != $house_id){
        			return array('status'=>"fail",'msg'=>'删除失败，参数错误');
        		}
        		DB::beginTransaction();
		        try{
		        	LikeComment::where('comment_id',$request->comment_id)->delete();
		        	$comment->delete();
		        	DB::commit();
		        }catch(Exception $e){
		            DB::rollback();
		            return array('status'=>"fail",'msg'=>'删除失败');
		        }
        	}else{
        		return array('status'=>"fail",'msg'=>'删除失败，评论或不存在');
        	}
        }       
        return array('status'=>"success",'msg'=>'删除成功');
	}

}