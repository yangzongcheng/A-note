#etcd
```text

etcd 是用于共享配置和服务发现的分布式、一致性的 KV 存储系统。

etcd 是一个分布式的、可靠的 key-value 存储系统，它用于存储分布式系统中的关键数据，
这个定义非常重要。

Kubernetes 将自身所用的状态存储在 etcd 中，其状态数据的高可用交给 etcd 来解决，
Kubernetes 系统自身不需要再应对复杂的分布式系统状态处理，自身的系统架构得到了大幅的简化。
```

>架构图

![](https://pic2.zhimg.com/v2-7ca4c212da420104941d030ff2854729_r.jpg)