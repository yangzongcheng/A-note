<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2018/3/26
 * Time: 17:45
 *
 * 选择排序算法
 */
function  selectSort($arr) {
    // 实现思路
    // 双重循环完成，外层控制轮数，当前的最小值，内层控制比较次数\
    // 获取长度
    $length = count($arr);
    for($i =0; $i < $length; $i++) {
           // 假设最小值的位置
               $p = $i;
          // 使用假设的最小值和其他值比较，找到当前的最小值
               for($j = $i +1; $j < $length; $j++){
          // $arr[$p] 是已知的当前最小值
          // 判断当前循环值和已知最小值的比较，当发下更小的值时记录下键，并进行下一次比较
                   if($arr[$p] > $arr[$j]){
                       $p = $j;
          // 比假设的值更小
                   }
               }
          // 通过内部for循环找到了当前最小值的key,并保存在$p中
          // 判断 日光当前$p 中的键和假设的最小值的键不一致增将其互换
               if($p != $i){
                   $tmp     = $arr[$p];//最小值
                   $arr[$p] = $arr[$i];
                   $arr[$i] = $tmp;
               }
    }
// 返回最终结果

    return $arr;
}

$arr = [7,2,5,3,9,4,8,1];

var_dump(selectSort($arr));
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

