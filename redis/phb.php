<?php
/**
 * @param $data
 * 打印
 */
function dd($data)
{
    echo "<pre>" . print_r($data, true);
}

$redis = new redis();
$result = $redis->connect('127.0.0.1', 6379);
if ($result !== true) {
    echo "连接redis失败!";
    exit;
}

dd($redis->hGetAll('h'));

die;

//有序集合
//常见操作，
echo "1、添加成员到集合里<br/>";
//添加数据到key为sort里
//$redis->zAdd('sort', 80, 'mrtwenty'); //存在就修改， 不存在就添加到集合中去
//for ($i=0;$i<100000;$i++){
//    $redis->zAdd('sort', mt_rand(1,10000000), 'u_'.$i);
//}

//A如果不存在，就添加A进入，并初始化值为1，如果存在就给A加1，分数1也可以是负的，
$redis->zIncrBy('sort', 1, 'A');
//print_r($redis->zRange('sort', 0, -1));
echo "<hr/>";

echo "2、取出前三名<br/>";
$list = $redis->zRevRange('sort', 0, 2);
dd($list);

echo "取出前1000名,包括他们的值<br/>";
$list = $redis->zRevRange('sort', 0, 999, true);
dd($list);
echo "<hr/>";

echo "3、zDelete、zRem、zRemove 删除指定成员,返回的值1表示成功， 0表示失败<hr/>";
$result = $redis->zDelete('sort', 'A');

echo "4、取出某个成员的排名或者分数<br/>";
printf("返回集合中zhao的分数:%d<br/>", $redis->zScore('sort', 'zhao'));
//如果返回false表示不存在集合中，0表示第一名 zRevRank 从大到小排名 zRank    从小到大排名
printf("返回zhao在集合中的排名:%d<hr/>", $redis->zRevRank('sort', 'zhao'));

printf("5、统计数量:%d<hr/>", $redis->zSize('sort')); //也可以值zCard

printf("6、返回80分到100分之间的个数:%d<hr/>", $redis->zCount('sort', 80, 100));

echo "7、取出满足指定分数区间的成员,总小到大<br/>";
$list = $redis->zRangeByScore('sort', 90, 100);
//withscores表示是否取出分数，limit用来实现分页
// $list = $redis->zRangeByScore('sort', 90, 100, ['withscores' => true, 'limit' => [0, 2]]);
print_r($list);
echo "<hr/>";

//8、取出所有的key，0表示从第一个开始取，-1表示最后一个
echo "8、取出所有成员:<br/>";
//$list = $redis->zRange('sort', 0, -1);
//print_r($list);
