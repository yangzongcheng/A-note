<?php
include_once '../../func.php';
$array = array(
    array('id' => 1, 'pid' => 0, 'name' => '河北省'),
    array('id' => 2, 'pid' => 0, 'name' => '北京市'),
    array('id' => 3, 'pid' => 1, 'name' => '邯郸市'),
    array('id' => 4, 'pid' => 2, 'name' => '朝阳区'),
    array('id' => 5, 'pid' => 2, 'name' => '通州区'),
    array('id' => 6, 'pid' => 4, 'name' => '望京'),
    array('id' => 7, 'pid' => 4, 'name' => '酒仙桥'),
    array('id' => 8, 'pid' => 3, 'name' => '永年区'),
    array('id' => 9, 'pid' => 1, 'name' => '武安市'),
    array('id' => 10, 'pid' => 0, 'name' => '四川省'),
    array('id' => 11, 'pid' => 10, 'name' => '成都市'),
    array('id' => 12, 'pid' => 11, 'name' => '武侯区'),
    array('id' => 13, 'pid' => 12, 'name' => '共和村'),
);



/**
 * 递归实现无限极分类
 * @param $array 分类数据
 * @param $pid 父ID
 * @param $level 分类级别
 * @return $list 分好类的数组 直接遍历即可 $level可以用来遍历缩进
 */

function getTree($array, $pid =0, $level = 0){
    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
    static $list = [];
    foreach ($array as $key => $value){
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['pid'] == $pid){
            //父节点为根节点的节点,级别为0，也就是第一级
            $value['level'] = $level;
            //把数组放到list中
            $list[] = $value;
            //把这个节点从数组中移除,减少后续递归消耗
            unset($array[$key]);
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            getTree($array, $value['id'], $level+1);
        }
    }
    return $list;
}


function generateTree($array){
    //第一步 构造数据
    $items = array();
    foreach($array as $value){
        $items[$value['id']] = $value;
    }

    //第一步很容易就能看懂，就是构造数据，现在咱们仔细说一下第二步
    $tree = array();
    //遍历构造的数据
    foreach($items as $key => $value){
        //如果pid这个节点存在
        if(isset($items[$value['pid']])){
            //把当前的$value放到pid节点的son中 注意 这里传递的是引用 为什么呢？
            $items[$value['pid']]['son'][] = &$items[$key];
        }else{
            $tree[] = &$items[$key];
        }
    }

    return $tree;
}



$arrays = generateTree($array);
dd($arrays);


/*
 * 获得递归完的数据,遍历生成分类
 */
//$array = getTree($array);
//
//foreach($array as $value){
//       echo str_repeat('--', $value['level']), $value['name'].'<br />';
//}



