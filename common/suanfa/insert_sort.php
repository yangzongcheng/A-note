<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2018/3/27
 * Time: 15:52
 */

/**
 * @param $arr
 * @return mixed
 * 插入排序
 */
function insert_sort($arr){
    $len=count($arr);
    for($i=1; $i<$len; $i++) {
        //获得当前需要比较的元素值。
        $tmp = $arr[$i];
        //内层循环控制 比较 并 插入
        for($j=$i-1; $j>=0; $j--){
            //$arr[$i];//需要插入的元素; $arr[$j];//需要比较的元素
            if($tmp < $arr[$j]) {
                //发现插入的元素要小，交换位置
                //将后边的元素与前面的元素互换
                $arr[$j+1] = $arr[$j];
                //将前面的数设置为 当前需要交换的数
                $arr[$j] = $tmp;
            }else {
                //如果碰到不需要移动的元素
                //由于是已经排序好是数组，则前面的就不需要再次比较了。
                break;
            }
        }
    }
//将这个元素 插入到已经排序好的序列内。
//返回
    return $arr;
}
$arr = array(2,5,1,3,4);


var_dump(insert_sort($arr));