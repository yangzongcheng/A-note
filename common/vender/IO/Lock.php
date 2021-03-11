<?php

namespace luoyy\IO;

use \Closure;

/**
 * IO流程锁
 */
class Lock
{

    /**
     * 锁文件
     */
    const FILE = __DIR__.'/lockFile.lock';
    const FILE1 = __DIR__.'/lock.txt';

    /**
     * [work 同步锁]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2017-08-16T16:28:47+0800
     * @copyright (c)                      ZiShang520 All           Rights Reserved
     * @param     [type]                   $func      [description]
     * @return    [type]                              [description]
     */
    public static function work(Closure $func)
    {
        if ($func instanceof Closure) {
            $fp = fopen(self::FILE, "r");
            if (flock($fp, LOCK_EX)) {
                $func();
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }
    }

    /**
     * [asyncWork 异步锁]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2017-08-16T16:35:47+0800
     * @copyright (c)                      ZiShang520 All           Rights Reserved
     * @param     Closure                  $func      [description]
     * @param     Closure                  $func2     [description]
     * @return    [type]                              [description]
     */
    public static function asyncWork(Closure $func, Closure $func2)
    {
        if ($func instanceof Closure && $func2 instanceof Closure) {
            $fp = fopen(self::FILE, "w+");
            //发现文件打开加锁
            if (flock($fp, LOCK_EX | LOCK_NB)) {
                $func();
                //走完逻辑解锁
                flock($fp, LOCK_UN);
            } else {
                $func2();
            }
            fclose($fp);
        }
    }


}
