<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2017/12/19
 * Time: 10:51
 * 公用数组函数
 */

/**
 * @param $multi_array
 * @param $sort_key
 * @param int $sort SORT_ASC - 默认。按升序排列 (A-Z)。 SORT_DESC - 按降序排列 (Z-A)。
 * @return bool
 * 二维数组排序
 */
function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){
    if(is_array($multi_array)){
        foreach ($multi_array as $row_array){
            if(is_array($row_array)){
                $key_array[] = $row_array[$sort_key];
            }else{
                return false;
            }
        }
    }else{
        return false;
    }
    array_multisort($key_array,$sort,$multi_array);
    return $multi_array;
}

/**
 * @param $array
 * @return mixed
 * 去掉数组中的空值 一维数组
 */
function array_emptey($array){
    if($array){
        foreach ($array as $key=>$val){
            if(!$val){
                unset($array[$key]);
            }
        }
    }
    return $array;
}

/**
 * @param $array
 * @param $val
 * @return mixed
 * 删除数组制定的val
 */
function array_remove($array,$val){
    foreach ($array as $k=>$v){
        if($v==$val){
            unset($array[$k]);
            break;
        }
    }
    return $array;
}

if(!function_exists('remove_arraych')){
    /**
     * @param $array
     * @return array
     * 去掉二维数组重复的值
     */
    function remove_arraych($array,$keys){
        $result=array();
        foreach ($array as $key => $value) {
            $has = false;
            foreach($result as $val){
                if($val[$keys]==$value[$keys]){
                    $has = true;
                    break;
                }
            }
            if(!$has)
                $result[]=$value;
        }
        return $result;
    }
}



/**
 * @param $data
 * @return array
 * 去调 二维数组的空值
 */
function del_array_null($data){

    if(is_array($data) &&count($data)>0){
        foreach ($data as $key=>$val){
            if(is_array($val) && count($data)>0){
                foreach ($val as $k=>$v){
                    if(!$v){
                        $data[$key][$k] = '';
                    }
                }
            }else{
                if(empty($val) || $val=='null' || $val==null || !$val){
                    $data[$key] = '';
                    if(is_array($val)){
                        $data[$key] =[];
                    }
                }
            }
        }
    }

    return $data;
}

/*
 * XML文件转换为数组
 */
function xml_to_array($xml)
{
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $values;
}