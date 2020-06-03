# RTMP推流服务器搭建    ·

标签（空格分隔）： 直播系统
---
官方文档：https://github.com/ossrs/srs/wiki/v1_CN_Home?spm=a2c4e.10696291.0.0.687519a4izveKe
注意：SRS也不支持Windows系统如果使用Windows搭建则可使用Docker实现
## 1 单机部署
参考链接：https://github.com/ossrs/srs/tree/1.0release#usage
```
工具安装：yum  install -y  git   make
源码拉取：git clone https://github.com/ossrs/srs
编译：进入目录(srs/trunk)运行./configure && make
启动：进入目录(srs/trunk)运行././objs/srs -c conf/srs.conf

```

## 2 鉴权
A 编写SRS鉴权配置文件（conf/authentication.conf）
```
listen              1934;
srs_log_tank        file;
srs_log_file        ./objs/logs/yuanzan2.log;
max_connections     1000;
vhost __defaultVhost__ {
    gop_cache       off;
    queue_length    10;
    min_latency     on;
    mr {
        enabled     off;
    }
    mw_latency      100;
    tcp_nodelay     on;
    play {
        gop_cache       off;
        queue_length    10;
        mw_latency      100;
    }
    publish {
        mr off;
    }
    http_hooks {
        enabled         on;
        on_connect      http://47.75.111.156/api/public/?service=Live.checkLiveSing;
        on_close        http://47.75.111.156/api/public/?service=Live.checkLiveSing;
        on_publish      http://47.75.111.156/api/public/?service=Live.checkLiveSing;
        on_unpublish    http://47.75.111.156/api/public/?service=Live.checkLiveSing;
        on_play         http://47.75.111.156/api/public/?service=Live.checkLiveSing;
        on_stop         http://47.75.111.156/api/public/?service=Live.checkLiveSing;
    }
   cluster {
        #集群的模式，对于源站集群，值应该是local。
        mode            local;
        #是否开启源站集群
        origin_cluster  on;
        #源站集群中的其他源站的HTTP API地址
        coworkers       127.0.0.1:1935;
   }
}

```
B 启动SRS
```
./objs/srs -c conf/authentication.conf
```
备注: 将47.75.111.156修改为实际php访问服务器ip地址
## 3 集群部署
参考链接：https://github.com/ossrs/srs/wiki/v1_CN_SampleRTMPCluster

##### A 编写SRS源站配置文件（conf/origin.conf）
```
listen              1935;
max_connections     1000;
pid                 objs/origin.pid;
srs_log_file        ./objs/origin.log;
vhost __defaultVhost__ {
    cluster {
        #集群的模式，对于源站集群，值应该是local。
        mode            local;
        #是否开启源站集群
        origin_cluster  on;
        #源站集群中的其他源站的HTTP API地址
        coworkers       127.0.0.1:1934;
   }     
}
```
##### B 编写SRS边缘配置文件（conf/edge.conf）
```
# conf/edge.conf
listen              19350;
max_connections     1000;
pid                 objs/edge.pid;
srs_log_file        ./objs/edge.log;
vhost __defaultVhost__ {
    cluster {
        #集群的模式，对于边缘站集群，值应该是remote。
        mode            remote;
        #源站集群中所有源站流地址
        origin       127.0.0.1:1934;
   }     
}
```
##### C 启动SRS
```
./objs/srs -c conf/origin.conf &
./objs/srs -c conf/edge.conf &  
```
备注：请将所有实例的IP地址192.168.1.170都换成部署的服务器IP地址。

