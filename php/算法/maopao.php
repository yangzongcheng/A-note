<?php


$arr = [4, 5, 6, 7,8,9, 3, 2, 1];

#简单冒泡
function maopao2($arr)
{
    $len = count($arr);
    $count = 0;
    for ($i = 0; $i < $len; $i++) {
        for ($j = $len - 1; $j > 0; $j--) {
            $count++;
            if ($arr[$j] < $arr[$j - 1]) {
                $tmp = $arr[$j - 1];
                $arr[$j - 1] = $arr[$j];
                $arr[$j] = $tmp;
                print_r(['count' => $count, 'arr' => json_encode($arr), 'msg' => '命中']);
            } else {
                print_r(['count' => $count, 'arr' => json_encode($arr)]);
            }
        }
    }

    return $arr;
}


#一般冒泡
#第一轮内存循环结束后最小的数一定在最左边，所以第二轮比对时就没有必要再去比对数据的第一个key的值
function maopao3(array $arr)
{
    $count = 0;
    $length = count($arr);
    for ($i = 0; $i < $length - 1; $i++) {
        //从后往前逐层上浮小的元素，比对过的将不会再重复比对
        for ($j = $length - 2; $j >= $i; $j--) {
            $count++;
            //两两比较相邻记录
            if ($arr[$j] > $arr[$j + 1]) {
                $tmp = $arr[$j + 1];
                $arr[$j + 1] = $arr[$j];
                $arr[$j] = $tmp;
                print_r(['count' => $count, 'arr' => json_encode($arr), 'msg' => '命中', 'i' => $i, 'j' => $j]);
            } else {
                print_r(['count' => $count, 'arr' => json_encode($arr), 'i' => $i, 'j' => $j]);
            }
        }
    }
}

#终极冒泡
#如果我们待排序的序列是{2,1,3,4,5,6,7,8,9}，也就是说，除了第一和第二个关键字需要交换外，
#别的都已经是正常的顺序了。当 i = 0 时，交换了 2 和 1 ，此时的序列已经是有序的了，
#但是算法仍然不依不挠地将 i = 2 到 9 以及每个循环中的 j 循环都执行了一遍，
#尽管并没有交换数据，但是之后的大量比较还是大大地多余了。
#当 i = 2 时，我们已经对 9 与 8，8 与 7，·······，3 与 2 做了比较，没有任何数据交换，
#这就说明此序列已经有序，不需要再继续后面的循判断工作了(后面的工作也是不会发生任何数据交换，再做也是没有意义了)。
#为了实现这个想法，我们需要改进一下代码，增加一个标记变量 flag 来实现这一算法的改进：

function maopao4(array $arr)
{
    $length = count($arr);
    $flag = TRUE;
    $count = 0;
    for ($i = 0; ($i < $length - 1) && $flag; $i++) {
        $flag = FALSE;
        for ($j = $length - 2; $j >= $i; $j--) {
            $count++;
            //两两比较相邻记录
            if ($arr[$j] > $arr[$j + 1]) {
                $tmp = $arr[$j + 1];
                $arr[$j + 1] = $arr[$j];
                $arr[$j] = $tmp;
                $flag = TRUE;
                print_r(['count' => $count, 'arr' => json_encode($arr), 'msg' => '命中']);
            } else {
                print_r(['count' => $count, 'arr' => json_encode($arr)]);
            }
        }
    }
}

maopao4($arr);

