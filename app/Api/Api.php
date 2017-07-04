<?php

namespace App\Api;


class Api
{
    public static function sendMessage($mobile="",$content=""){
        $message = new Message;
        return $message->sendCode($mobile,$content);
    }
    //拼音转换
    public static function pinyin($Chinese){
        $pinyin = new Pinyin();  // 小内存型(默认)
        $pinyin_array = $pinyin->convert($Chinese);
        $pinyin_string = '';
        foreach ($pinyin_array as $value) {
            $pinyin_string.=$value;
        }
        return $pinyin_string;
    }

    public static function createBrandTable($prefix=''){
        if($prefix){
            $brand_table = new BrandTable;
            $brand_table->createTables($prefix);
            return true;
        }else{
            return false;
        }
    }

    public static function deleteBrandTable($prefix=''){
        if($prefix){
            $brand_table = new BrandTable;
            $brand_table->dropTables($prefix);
            return true;
        }else{
            return false;
        }
    }

    public static function exportExcel($name,$data){
        $excel = new Excel($name,$data);
        $excel->export();
    }

    public static function imageUpload($file,$sub_dir="images"){
        $upload = new Upload($file,$sub_dir);
        return $upload->upload('image');
    }

    public static function multiImageUpload($file,$sub_dir="images"){
//	    dd($file);
        $upload = new Upload($file,$sub_dir);
        return $upload->multiUpload('image');
    }

    public static function videoUpload($file,$sub_dir="images"){
        $upload = new Upload($file,$sub_dir);
        return $upload->upload('video');
    }

    public static function multiVideoUpload($file,$sub_dir="images"){
        $upload = new Upload($file,$sub_dir);
        return $upload->multiUpload('video');
    }

    public static function fileUpload($file,$sub_dir="images"){
        $upload = new Upload($file,$sub_dir);
        return $upload->upload('file');
    }

    public static function multiFileUpload($file,$sub_dir="images"){
        $upload = new Upload($file,$sub_dir);
        return $upload->multiUpload('file');
    }

    public static function audioUpload($file,$sub_dir="images"){
        $upload = new Upload($file,$sub_dir);
        return $upload->upload('audio');
    }

    public static function multiAudioUpload($file,$sub_dir="images"){
        $upload = new Upload($file,$sub_dir);
        return $upload->multiUpload('audio');
    }
}