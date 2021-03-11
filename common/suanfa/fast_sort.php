<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2018/3/26
 * Time: 17:22
 * 快速排序算法
 */
function quickSort($arr) {
    // 获取数组长度
    $length = count($arr);
   // 判断长度是否需要继续二分比较
    if ($length <= 1) {
        return $arr;
    }
    // 定义基准元素
    $base = $arr[0];

    // 定义两个空数组，用于存放和基准元素的比较后的结果
    $left  = [];
    $right = [];
     // 遍历数组
    for($i =1; $i < $length; $i++) {
     // 和基准元素作比较 分别和基元素做比较 大的放right 小的放left
        if($arr[$i] > $base) {
            $right[] = $arr[$i];
        } else {
            $left[] = $arr[$i];
        }
    }

     // 然后递归分别处理left和right
    $left  = quickSort($left);
    $right = quickSort($right);

     // 合并
    $all_arr = array_merge($left, [$base], $right);

    return $all_arr;
}


$arr = [1,4,2,3];

var_dump(quickSort($arr));

//打印
//array (size=8)
//  0 => int 1
//  1 => int 2
//  2 => int 3
//  3 => int 4
//  4 => int 5
//  5 => int 7
//  6 => int 8
//  7 => int 9
