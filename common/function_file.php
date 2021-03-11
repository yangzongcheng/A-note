<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2018/1/19
 * Time: 18:14
 * 文件 操作函数
 */


/**
 * @param $filename 文件路径
 * @param $name 下载的文件名 包括后缀
 * $type 为文件类型  如 pdf  doc 等 默认不加 因为 Safari 浏览器不加  Content-type 会在文件下载时默认加上 .html 后缀
 */
function download_file($filename,$name,$type=''){
    if($type){
        header('Content-type: application/'.$type);
    }

    header('Content-Disposition: attachment; filename="'.$name.'"');
    readfile($filename);

//将文件内容读取出来并直接输出，以便下载
    readfile($filename);
}



if(!function_exists('is_upload_file')){
    /**
     * @return bool
     * 判断是否有文件上传
     */
    function is_upload_file($name){
        if(isset($_FILES[$name])){
            $file = $_FILES[$name];
            if(is_array($file['size'])){
                //多图片
                if($file['size'][0]>0){
                    return true;
                }else{
                    return false;
                }

            }else{
                //单个图片
                if($file['size']>0){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }

    }
}