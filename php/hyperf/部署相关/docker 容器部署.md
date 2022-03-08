###docker 容器实现热重启

[参考链接](https://zhuanlan.zhihu.com/p/157594226)
```text
思路：
服务器单机做负载均衡
创建两个容器对应两个端口端口

```

```text
nginx

upstream legal_api {
    server 127.0.0.1:10090;
    server 127.0.0.1:10091;
}
server {
    listen       80;
    server_name  legal-api.xxx.cn;
    location / {
        proxy_pass          http://legal_api;
        proxy_set_header    Host                $host:$server_port;
        proxy_set_header    X-Real-IP           $remote_addr;
        proxy_set_header    X-Real-PORT         $remote_port;
        proxy_set_header    X-Forwarded-For     $proxy_add_x_forwarded_for;
    }
}
```

```text

# 拉取最新的镜像
docker pull registry.cn-shanghai.aliyuncs.com/xxx/legal_api:latest
sleep 1

# 关闭正在运行的 legal_api 容器
docker stop legal_api && docker rm legal_api
sleep 1

# 启动新的容器
docker run -d --restart always -p 10090:9501 -v /www/limx/legal_api.env:/opt/www/.env --name legal_api registry.cn-shanghai.aliyuncs.com/xxx/legal_api:latest
sleep 1

# 关闭正在运行的 legal_api2 容器
docker stop legal_api2 && docker rm legal_api2
sleep 1

# 启动新的容器
docker run -d --restart always -p 10091:9501 -v /www/limx/legal_api.env:/opt/www/.env --name legal_api2 registry.cn-shanghai.aliyuncs.com/xxx/legal_api:latest

```