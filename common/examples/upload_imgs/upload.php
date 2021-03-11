<?php

//

//var_dump($_FILES);

//print_r(getInfo('./upload/png.png'));die;
if(empty($_FILES))
    die(json_encode(array('code'=>-1,'msg'=>'没有图片上传')));

$file = $_FILES['file'];

//print_r($file);die;
if($file['error']!=0 || $file['size']< 1 ){
    die(json_encode(array('code'=>-1,'msg'=>'error','id'=>$_POST['id'])));
}

move_uploaded_file( $file['tmp_name'], "./upload/".$file['name']);

$src = "/upload/".$file['name'];








exit(json_encode(array('code'=>1, 'msg'=>'ok','src'=>$src)));
