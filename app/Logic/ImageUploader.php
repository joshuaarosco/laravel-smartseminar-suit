<?php

namespace App\Logic;

/*
*
* Models used for this class
*/

/*
*
* Classes used for this class
*/
use Carbon\Carbon;
use Str, File, Image, URL;

class ImageUploader {

    /**
    *
    *@param App\Http\Requests\RequestRequest $request
    *@param string $request
    *
    *@return array
    */
    static public function upload($file, $image_directory = "uploads"){
        
        $storage = env('IMAGE_STORAGE', "file");

        $ext = $file->getClientOriginalExtension();
        $thumbnail = ['height' => 500, 'width' => 500];
        $path_directory = $image_directory."/".Carbon::now()->format('Ymd');
        $resized_directory = $image_directory."/".Carbon::now()->format('Ymd')."/resized";
        $thumb_directory = $image_directory."/".Carbon::now()->format('Ymd')."/thumbnails";

        if (!File::exists($path_directory)){
            File::makeDirectory($path_directory, $mode = 0777, true, true);
        }

        if (!File::exists($resized_directory)){
            File::makeDirectory($resized_directory, $mode = 0777, true, true);
        }

        if (!File::exists($thumb_directory)){
            File::makeDirectory($thumb_directory, $mode = 0777, true, true);
        }

        $filename = str_random(20). date("mdYhs") . "." . $ext;

        $file->move($path_directory, $filename);
        if(in_array(Str::lower($ext), ['jpg','png','jpeg','gif','webp'])){
            Image::make("{$path_directory}/{$filename}")->save("{$resized_directory}/{$filename}",95);
            Image::make("{$path_directory}/{$filename}")->crop($thumbnail['width'],$thumbnail['height'])->save("{$thumb_directory}/{$filename}",95);
        }

        return [ "path" => $image_directory, "directory" => URL::to($path_directory), "filename" => $filename ];

    }
}
