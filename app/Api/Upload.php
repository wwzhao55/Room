<?php

namespace App\Api;
use Storage;

class Upload
{
    /**
     * 文件上传
     * @param $file 文件实例
     * @param $sub_floder 文件子目录 默认为images
     */
    private $file ;
    private $sub_floder;

    function __construct($file='',$sub_floder='images'){

        $this->file = $file;
        $this->sub_floder = $sub_floder;

    }

    public  function upload($type='image'){
//        dd($this->sub_floder);
        if(!is_object($this->file)){
            return false;
        }
//        dd($this->sub_floder);
        switch ($type) {
            case 'image':
                if(!$this->checkLimit($this->file,'image')){
                    return false;
                }
                break;
            case 'file':
                if(!$this->checkLimit($this->file,'file')){
                    return false;
                }

                break;
            case 'video':
                if(!$this->checkLimit($this->file,'video')){
                    return false;
                }

                break;
            case 'audio':
                if(!$this->checkLimit($this->file,'audio')){
                    return false;
                }

                break;
            default:
                return false;
        }
//dd(public_path().config('upload.uploads.rootpath').'/'.$this->sub_floder);
        if($this->file->isValid()){
            /*if(!has_upload_dir($this->sub_floder)){
                Storage::makeDirectory($this->sub_floder);
            }*/
            $destinationPath = public_path().config('upload.uploads.rootpath').'/'.$this->sub_floder;
            $fileExtention = $this->file->getClientOriginalExtension();
            $fileName = md5(time().rand(0,10000));
            $result = $this->file->move($destinationPath, $fileName.'.'.$fileExtention);
            if($result){
                return config('upload.uploads.rootpath').'/'.$this->sub_floder.'/'.$fileName.'.'.$fileExtention;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function multiUpload($type='image'){
        if(!is_array($this->file)){
            return false;
        }
        $file_path_array = array();
        foreach($this->file as $list){
            if(!is_object($list)){
                continue;
            }
            switch ($type) {
                case 'image':
                    if(!$this->checkLimit($list,'image')){
                        continue 2;
                    }
                    break;
                case 'file':
                    if(!$this->checkLimit($list,'file')){
                        continue 2;
                    }
                    break;
                case 'video':
                    if(!$this->checkLimit($list,'video')){
                        continue 2;
                    }
                    break;
                case 'audio':
                    if(!$this->checkLimit($list,'audio')){
                        continue 2;
                    }
                    break;
                default:
                    return false;
            }
            if($list->isValid()){
                if(!has_upload_dir($this->sub_floder)){
                    Storage::makeDirectory($this->sub_floder);
                }
                $destinationPath = public_path().config('upload.uploads.rootpath').'/'.$this->sub_floder;
                $fileExtention = $list->getClientOriginalExtension();
                $fileName = md5(time().rand(0,10000));
                $result = $list->move($destinationPath, $fileName.'.'.$fileExtention);
                if($result){
                    array_push($file_path_array, config('upload.uploads.rootpath').'/'.$this->sub_floder.'/'.$fileName.'.'.$fileExtention) ;
                }
            }
        }
        $success_length = count($file_path_array);
        if($success_length){
            return $file_path_array;
        }else{
            return false;
        }
    }

    #---------type video image file audio
    private function checkMime($mime,$type){
        switch ($type) {
            case 'image':
                $mime_limit = config('upload.uploads.image_mime');
                break;
            case 'video':
                $mime_limit = config('upload.uploads.video_mime');

                break;
            case 'file':
                $mime_limit = config('upload.uploads.file_mime');

                break;
            case 'audio':
                $mime_limit = config('upload.uploads.audio_mime');

                break;
            default:
                return false;
                break;

        }
        if(count($mime_limit)==0){
            return true;
        }
        if(in_array($mime, $mime_limit)){
            return true;
        }else{
            return false;
        }
    }

    private function checkExtention($ext,$type){
        switch ($type) {
            case 'image':
                $extention_limit = config('upload.uploads.image_extention');

                break;
            case 'video':
                $extention_limit = config('upload.uploads.video_extention');

                break;
            case 'file':
                $extention_limit = config('upload.uploads.file_extention');

                break;
            case 'audio':
                $extention_limit = config('upload.uploads.audio_extention');

                break;
            default:
                return false;
                break;

        }
        if(count($extention_limit)==0){
            return true;
        }
        if(in_array($ext, $extention_limit)){
            return true;
        }else{
            return false;
        }
    }

    private function checkSize($size,$type){
        switch ($type) {
            case 'image':
                $size_limit = config('upload.uploads.image_size');

                break;
            case 'video':
                $size_limit = config('upload.uploads.video_size');

                break;
            case 'file':
                $size_limit = config('upload.uploads.file_size');

                break;
            case 'audio':
                $size_limit = config('upload.uploads.audio_size');

                break;
            default:
                return false;
                break;
        }
        if($size_limit == 0){
            return true;
        }
        if($size_limit>=$size){
            return true;
        }else{
            return false;
        }
    }
    private function checkLimit($file,$type){
        $mime = $file->getMimeType();
        $ext = $file->getClientOriginalExtension();
        $size = $file->getClientSize();
        $mime_result = $this->checkMime($mime,$type);
        $ext_result = $this->checkExtention($ext,$type);
        $size_result = $this->checkSize($size,$type);
//        dd($mime_result);
        if($mime_result&&$ext_result&&$size_result){
            return true;
        }else{
            return false;
        }
    }


}