<?php
/**
 * User: hzq
 */
#-------------文件上传配置文件
return [
    'uploads' => [
        'default_headimg'=>'/images/headimg/default.jpg',
        //文件上传根路径相对于public
        'rootpath' => '/uploads',
        //图片支持扩展名
        'image_extention'=>[
            'jpg',
            'JPG',
            'bmp',
            'png',
            'jpeg',
            'ico',
            'svg',
        ],
        //图片大小 0不限制
        'image_size'=> 0,
        'image_mime'=>[
            'image/x-ms-bmp',
            'image/bmp',
            'image/gif',
            'image/jpeg',
            'image/x-icon',
            'image/png',
            'image/svg+xml',
        ],
        'video_extention'=>[
            'mp4',
            'avi',
            'mov',
            'mpg',
        ],
        'video_size'=> 0,
        'video_mime'=>[
            'video/mp4',
            'video/x-msvideo',
            'video/quicktime',
            'video/mpeg',
        ],
        'file_size'=>0,
        'file_extention'=>[
            'xla',
            'xlc',
            'xls',
            'xlm',
            'pdf',
            'htm',
            'html',
            'txt',
            'apk',
        ],
        'file_mime'=>[
            'text/html',
            'text/plain',
            'application/vnd.ms-excel',
            'application/pdf',
            'application/vnd.android',
//            'application/vnd.Android.package-archive',
        ],
        'audio_size'=>0,
        'audio_extention'=>[
            'mp3',
            'wav',
            'aac',
        ],
        'audio_mime'=>[
            'audio/mpeg',
            'audio/x-wav',
            'audio/x-aac',

        ],
        'error_code'=>[

        ],
    ],
];