<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2018/3/26
 * Time: 17:34
 * 冒泡算法
 */
function maop_sort($arr){
    $count = count($arr);
     //外层控制排序轮次
     // i必须从1开始
    for($i=1; $i<=$count; $i++){
        //内层控制每轮比较次数 循环完会把最大的值放到最后
        for($j=0; $j<$count-$i; $j++){
            if($arr[$j] > $arr[$j+1]){
                $temp        = $arr[$j];
                $arr[$j]     = $arr[$j+1];//小的放在前面
                $arr[$j+1]   = $temp; //大的放在后面达到排序(从小到大)效果
                //以上两行代码的顺序不能换  不然 $arr[$j+1] 会被覆盖
               }
            }

        }
        return $arr;
}

$arr = [1,4,5,3,2];

var_dump(maop_sort($arr));

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