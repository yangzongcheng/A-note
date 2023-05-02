####swoole协程

```php
Co\run(function () {
    echo "coro ".co::getcid()." start\n";
    go(function () {
        echo "coro ".co::getcid()." start\n";
        co::sleep(.2);
        echo "coro ".co::getcid()." end\n";
    });
    echo "coro ".co::getcid()." do not wait children coroutine\n";
    co::sleep(.1);
    echo "coro ".co::getcid()." end\n";
});
```

####原生PHP实现:生成器 yield

[https://www.cnblogs.com/zuochuang/p/8176868.html]()
```text
概念：生成器yield关键字不是返回值，他的专业术语叫产出值，只是生成一个值

优点
生成器会对PHP应用的性能有非常大的影响
PHP代码运行时节省大量的内存
比较适合计算大量的数据
```

###yied
```php
function createRange($number){
    for($i=0;$i<$number;$i++){
        yield time();
    }
}

$result = createRange(10); // 这里调用上面我们创建的函数
foreach($result as $value){
    sleep(1);
    echo $value."\n";
}
```

```$xslt
输出：
1606887279
1606887280
1606887281
1606887282
1606887283
1606887284
1606887285
1606887286
1606887287
1606887288

生成的时间戳并不一样
使用生成器时： createRange 的值不是一次性快速生成，而是依赖于 foreach 循环。
foreach 循环一次， for 执行一次。

代码执行过程。

1、首先调用 createRange 函数，传入参数10，但是 for 值执行了一次然后停止了，并且告诉 foreach 第一次循环可以用的值。
2、foreach 开始对 $result 循环，进来首先 sleep(1) ，然后开始使用 for 给的一个值执行输出。
3、foreach 准备第二次循环，开始第二次循环之前，它向 for 循环又请求了一次。
4、for 循环于是又执行了一次，将生成的时间戳告诉 foreach .
5、foreach 拿到第二个值，并且输出。由于 foreach 中 sleep(1) ，所以， for 循环延迟了1秒生成当前时间

所以，整个代码执行中，始终只有一个记录值参与循环，内存中也只有一条信息。
无论开始传入的 $number 有多大，由于并不会立即生成所有结果集，所以内存始终是一条循环的值。


代码中 foreach 循环的是什么？其实是PHP在使用生成器的时候，会返回一个 Generator 类的对象。
foreach 可以对该对象进行迭代，每一次迭代，PHP会通过 Generator 实例计算出下一次需要迭代的值。这样 foreach 就知道下一次需要迭代的值了。

而且，在运行中 for 循环执行后，会立即停止。等待 foreach 下次循环时候再次和
for  索要下次的值的时候，循环才会再执行一次，然后立即再次停止。直到不满足条件不执行结束。
```

