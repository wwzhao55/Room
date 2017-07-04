<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request,App\Models\House,Illuminate\Pagination\LengthAwarePaginator,Illuminate\Pagination\Paginator,App\Models\LikeHouse,App\Models\Comment;
use View,Response,DB,Validator;
require app_path('Api/WechatImg.php');
class MainController extends BaseController
{
    public function index(){
    	return View::make('room.index');
    }

    public function houseList(Request $request){
            $per = 10;
            $houses = House::select('houses.id','name','district','rent','score','good_img','bad_img');
            if($request->place != ''){
                $houses = $houses->where('name','like','%'.$request->place.'%');
            }
            if($request->has('district')){
                if($request->is_district==1)
                    $houses = $houses->where('district','like','%'.$request->district.'%');
                else{
                    $houses = $houses->where('city','like','%'.$request->district.'%');
                }
            }
            switch($request->order){
                case 1:
                    //点赞数
                    $houses = $houses->has('like','>=',10)->withCount('like')->withCount('comment')->orderby('like_count','desc')->paginate($per);
                    break;
                // case 2:
                //     //评论数
                //     $houses = $houses->leftJoin('comment','houses.id','=','comment.house_id')->get();
                //     break;
                case 2:
                    //距离
                    $validator = Validator::make($request->all(), [
                        'longitude'=>array('required','regex:/^[-]?(\d|([1-9]\d)|(1[0-7]\d)|(180))(\.\d*)?$/'),
                        'latitude'=>array('required','regex:/^[-]?(\d|([1-8]\d)|(90))(\.\d*)?$/'),
                    ]);
                    if ($validator->fails()) {
                        $warnings = $validator->messages();
                        $show_warning = $warnings->first();
                        return Response::Json(['status'=>'fail','msg'=>$show_warning]);
                    }
                    $lat = $request->latitude;
                    $long = $request->longitude;
                    //$houses = DB::select(DB::raw("select * from houses order by ACOS(SIN((".$lat." * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) +COS((".$lat." * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS((".$long." * 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380  asc"));
                    $houses = $houses->orderByRaw("ACOS(SIN((".$lat." * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) +COS((".$lat." * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS((".$long." * 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380",'asc')->withCount('like')->withCount('comment')->paginate($per);
                    break;
                default:
                     //默认（发布时间）
                    $houses = $houses->orderby('created_at','desc')->withCount('like')->withCount('comment')->paginate($per);  
                    break;
            }

            foreach ($houses as $key => $house) {
                if($house->score >= 3){
            		$house->main_img = explode(',', $house->good_img)[0];
                }else{
                    $house->main_img = explode(',', $house->bad_img)[0];
                }
                //$house->distance = ACOS(SIN(($lat* 3.1415) / 180 ) *SIN(($house->latitude * 3.1415) / 180 ) +COS(($lat * 3.1415) / 180 ) * COS(($house->latitude * 3.1415) / 180 ) *COS(($long * 3.1415) / 180 - ($house->longitude * 3.1415) / 180 ) ) * 6380;
            }
    	//分页
    	// if ($request->has('page')) {
     //              $current_page = $request->input('page');
     //              $current_page = $current_page <= 0 ? 1 :$current_page;
     //         } else {
     //              $current_page = 1;
     //         }
     //        $item = $houses->slice(($current_page-1)*$per, $per); 
     //        $total = $houses->count();
     //        $paginator =new LengthAwarePaginator($item, $total, $per, $current_page, [
     //              'path' => Paginator::resolveCurrentPath(), 
     //              'pageName' => 'page',  
     //         ]);
    	return Response::Json(['status'=>'success','msg'=>$houses]);
    }

    function caculateAKSN($ak, $sk, $url, $querystring_arrays, $method = 'GET')
    {  
        if ($method === 'POST'){  
            ksort($querystring_arrays);  
        }  
        $querystring = http_build_query($querystring_arrays);  
        return md5(urlencode($url.'?'.$querystring.$sk));  
    }
}
