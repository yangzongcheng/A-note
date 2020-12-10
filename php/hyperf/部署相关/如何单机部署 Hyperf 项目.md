###如何单机部署hyperf
>参考链接

```text
 对与有条件的团队 还是上 k8s 等, 做滚动更新
```

[Hyperf 生产环境, 不上Swarm, 不上 k8s, 如何做热更新?](https://zhuanlan.zhihu.com/p/136289395)

[如何单机部署 Hyperf 项目](https://zhuanlan.zhihu.com/p/157594226)



<br>

>存在问题
```text
停止服务的瞬间无法响应接口？
如何实现热更新功能？
```

>解决办法
```text
模拟docker集群的发布过程。
发布新的容器
添加对应的dns解析
卸载将要停止容器的dns解析
停止对应的容器
所以，可以写一个脚本来处理。

切换服务端口启动项目
刷新nginx到新的端口
去掉老的nginx映射
关掉老的服务

```