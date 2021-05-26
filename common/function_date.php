<?php
/**
 * Created by PhpStorm.
 * User: yangzc
 * Date: 2017/12/19
 * Time: 10:51
 * 公用日期函数
 */

//获取某个时间点的钱一天或后一天
date('Y-m-d',strtotime("2009-05-26 - 1 day"));

function yesTerdayAt(){
    date("Y-m-d",strtotime("-1 day")).' 00:00:00';
}

/**
 * @param bool $time 时间戳
 * @return false|int|string
 * 得到指定时间戳是当前季度的第几天，默认当前时间
 */
function seasonAt($time=false){
    $time = $time?:time();
    $month = date('m',$time);//当前月
    //季度规则  123为第一个季度
    $arr =[
        1=>[1,2,3],
        2=>[4,5,6],
        3=>[7,8,9],
        4=>[10,11,12]
    ];
    $season ='';
    foreach ($arr as $key=>$val){
        if(in_array($month,$val)){
            $season = $key;
            break;
        }
    }
    $newArr = $arr[$season];
    $numberDay =0;
    foreach ($newArr as $key=>$val){
        if($val ==$month){
            //当月
            $numberDay = $numberDay+date('d',$time);
            break;

        }else{
            $numberDay = $numberDay+(date('t',strtotime(date('Y',$time)."-{$val}")));
        }
    }

    return $numberDay;

}

/**
 * @param $a
 * @param $b
 * @return float
 * 根据两个时间戳 得到相隔天数
 */
function count_days($a,$b){
    $a_dt = getdate($a);
    $b_dt = getdate($b);

    $a_new = mktime(12, 0, 0, $a_dt['mon'], $a_dt['mday'], $a_dt['year']);

    $b_new = mktime(12, 0, 0, $b_dt['mon'], $b_dt['mday'], $b_dt['year']);
    return round(abs($a_new-$b_new)/86400);

}

/**
 * @return array
 * 返回当天的最大和最小时间戳
 */
function todayBeginEndAt($time=false){
    $time = $time?$time:time();

    $begin =date('Y-m-d', $time)." 00:00:00";
    $end = date('Y-m-d',$time)." 23:59:59";

    return [strtotime($begin),strtotime($end)];
}

/**
 * @return array
 * 返回当月第一天和最后一天
 */
function month_begin_end($time=false){
    $time = $time?$time:time();
    $begin =date('Y-m-01', strtotime(date("Y-m-d",$time))).' 00:00:00';
    $end = date('Y-m-d', strtotime("$begin +1 month -1 day")).' 23:59:59';

    return [strtotime($begin),strtotime($end)];
}
/**
 * 返回传入时间戳 当周的第一天和最后一天
 */
function week_begin_end($time = false){

    $time = $time?$time:time();

    $w =  date('w',$time);
    if($w==0){
        $time = $time-24*3600;
    }
    $start = date("Y-m-d H:i:s",mktime(0,0,0,date("m",$time),date("d",$time)-date("w",$time)+1,date("Y",$time)));
    $end = date("Y-m-d H:i:s",mktime(23,59,59,date("m",$time),date("d",$time)-date("w",$time)+7,date("Y",$time)));
    return [strtotime($start),strtotime($end)];
}

/**
 * @return array
 * 得到当前时的最大和最小时间戳
 */
function h_begin_end(){
    $min = date('Y-m-d H:')."00:00";
    $max = date('Y-m-d H:')."59:59";

    return [strtotime($min),strtotime($max)];
}


/**
 * 将秒转换为 分:秒
 * s int 秒数
 */
function second_to_min($s=0){
    //计算分钟
    //算法：将秒数除以60，然后下舍入，既得到分钟数
    $h    =    floor($s/60);
    //计算秒
    //算法：取得秒%60的余数，既得到秒数
    $s    =    $s%60;
    //如果只有一位数，前面增加一个0
    $h    =    (strlen($h)==1)?'0'.$h:$h;
    $s    =    (strlen($s)==1)?'0'.$s:$s;
    return [$h,$s];
}

/**
 * @param $time
 * @return bool|mixed
 * 根据时间戳得到星期
 */
function weekday($time)
{
    if(is_numeric($time))
    {
        $weekday = array('星期天','星期一','星期二','星期三','星期四','星期五','星期六');
        return $weekday[date('w', $time)];
    }
    return false;
}

/**
 * @param $time
 * @return false|string
 * 显示时间
 */
function sel_time($time){
    $now = time();
    $en = $now-$time;
    $msg = '';
    if($en<60){
        $msg = '刚刚';
    }else if($en<3600){
        $min =  intval(($en/60));
        $msg = $min.'分钟前';
    }else if($en<(24*3600)){
        $hin = floor($en/3600);
        $msg = $hin.'小时前';
    }else{
        //大于天
        $day = floor($en/(3600*24));
        if($day<30){
            $msg = $day.'天前';
        }else{
            $minYear = strtotime(date('Y').'-01-01 00:00:00');//今年最小时间戳
            if($time>$minYear){
                //今年之后
                $msg = date('m月d日',$time);
            }else{
                $msg = date('Y年m月d日',$time);
            }


        }
    }
    return $msg;
}
