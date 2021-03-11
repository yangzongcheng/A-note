<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2017/12/19
 * Time: 10:52
 * 公用sql函数
 */

if(!function_exists('sel_table_next_id')){
    /**
     * @param $table
     * @return bool
     * 查询某一个表的下一条增加数据的主键值
     */
    function sel_table_next_id($table){
        $sql = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES    
          WHERE TABLE_NAME='$table' ";
        $info = M()->query($sql);
        if($info){
            return $info[0]['auto_increment'];
        }else{
            return false;
        }
    }
}



/**
 * @param $param 二位数组 ['field'=>'值']
 * @param string $type  all 全模糊  left  左like  right 右like   =不模糊
 * @return array
 * 搜索条件
 * 适合tp3 语法其他框架自行修改
 * 模糊搜索
 */

function where_like($param,$status='and',$type='all'){
    $where= [];
    $re = [];
    if(is_array($param)){
        foreach ($param as $key=>$val){
            //判断接受到的参数是否有值
            if($v = getRequest($val)){
                if($type=='all'){
                    $where[$key] = ['like',"%{$v}%"];
                }elseif ($type=='left'){
                    $where[$key] = ['like',"%{$v}"];
                }elseif($type=='right'){
                    $where[$key] = ['like',"{$v}%"];
                }elseif ($type=='='){
                    $where[$key] = $v;
                }
            }
        }

    }

    if($status=='or' && $where){
        $where['_logic'] = 'or';
        $re[] = $where;
    }else{
        $re = $where;
    }

    return $re;
}


/**
 * @param $field 数据库 字段
 * @param $filed_time  时间值 如：1514736000_1519919999 中间通过符号隔开
 * $ex 时间得分割符号
 * $status 和前面的条件 以and  还是 or
 * @return array
 * 搜索时间范围  tp3 语法 其他框架自行修改
 * 2019年11月17日02:27:41
 * 2019年11月17日02:23:31
 *
 */
function where_time($field,$filed_time,$status='and',$ex='_'){

    $where = [];
    $re    = [];
    $time = getRequest($filed_time);
    if($time){
        //根据符号分割
        $time_arr = str_explode($time,$ex);
        if($time_arr){
            $start = $time_arr[0];
            $end    = $time_arr[1];
            $where[$field] = ['between',[$start,$end]];
        }
    }

    if($status=='or' && $where){
        $where['_logic'] = 'or';
        $re[] = $where;
    }else{
        $re = $where;
    }

    return $re;
}