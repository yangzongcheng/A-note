<?php

namespace Service\BigData\DataGovernance\EtlTask\Logic\Task;

use Provider\Helper;
use Provider\Timer;
use Zxedu\Particle\Error\NotExistsError;

/**
 * 执行运行etl任务锁获取逻辑
 * DateTime:  2023/5/16 10:34:27
 * ClassName: RunLock
 * @package Service\BigData\DataGovernance\EtlTask\Logic\Task
 */
class RunLock
{
    private static int $expire = 1800;#默认3600秒过期
    private static string $lockKey ='etl_task_run_handle_lock_';

    /**
     * 获取锁
     * DateTime: 2023/5/16 10:56:00
     * @param int $taskId
     * @return bool
     * @throws \Errors
     */
    public static function getLock(int $taskId):bool
    {
        $redis = Helper::redis();
        $lockKey  = self::getKey($taskId);
        $lockVale = time()+self::$expire;
        $lock = $redis->setnx($lockKey,$lockVale);
        if(!$lock && $redis->get($lockKey) > time())
        {
            throw new NotExistsError('任务在执行中,时间:'.Timer::dateTo(time()));
        }
        #执行到此处有两种情况 1senx执行获取锁成功 2key存在，但是值已经过期

        #再次判断
        #如果出现并发获取锁的情况此步骤可以防止锁被同时获取
        #如 当锁释放失败的时候 两个进程同时获取到锁此时先获取到锁的会把value重新赋值
        #第二个进程在获取的到的时候值已经是1进程赋值的value了此时获取锁将失败
        if(!$lock)
        {
            $oldVal = $redis->getSet($lockKey,$lockVale);
            if($oldVal > time())
            {
                #把值改回去
                $redis->set($lockKey,$oldVal);
                throw new NotExistsError('任务还在执行中,时间:'.Timer::dateTo(time()));
            }
        }
        #获取锁成功,设置过期时间
        $redis->expire($lockKey,self::$expire);
        return true;
    }

    /**
     * 释放锁
     * DateTime: 2023/5/16 10:55:06
     * @param int $taskId
     * @return bool
     * @throws \Errors
     */
    public static function delLock(int $taskId):bool
    {
        $redis = Helper::redis();
        $lockKey = self::getKey($taskId);
        if($redis->ttl($lockKey))
        {
            $redis->delete($lockKey);
        }
        return true;
    }

    /**
     * 获取key
     * DateTime: 2023/5/16 10:53:44
     * @param int $taskId
     * @return string
     */
    private static function getKey(int $taskId):string
    {
        return self::$lockKey.$taskId;
    }
}