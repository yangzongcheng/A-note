# nginx转发配置

标签（空格分隔）：直播系统

---

## NGINX安装
1 安装编译工具
```
yum -y install make zlib zlib-devel gcc-c++ libtool  openssl openssl-devel
```
2 安装PCRE
```
wget http://downloads.sourceforge.net/project/pcre/pcre/8.35/pcre-8.35.tar.gz
tar zxvf pcre-8.35.tar.gz
cd pcre-8.35
./configure
make && make install
验证是否安装成功
pcre-config --version
```
2 下载nginx
```
wget http://nginx.org/download/nginx-1.17.5.tar.gz
解压
tar zxvf nginx-1.17.5.tar.gz
修改名称
mv nginx-1.17.5 nginx
进入nginx 创建 logs 
cd nginx
mkdir logs
```
3 编译安装
```
###注意修改路径
./configure --prefix=/data/nginx --with-http_stub_status_module --with-http_ssl_module --with-pcre=/data/pcre-8.35  --with-stream=dynamic
make
make install
```
4 修改支持安卓,ios安装包下载
```
修改conf下的mime.types 
application/vnd.android.package-archive apk; 
application/iphone pxl ipa; 
```

## 1 NGINX 推拉流配置
A 推流转发配置rtmp-push.conf
```
#注意更换目录
load_module /data/nginx/objs/ngx_stream_module.so;
worker_processes  1;
error_log  logs/rtmp-push-error.log;
error_log  logs/rtmp-push-notice.log  notice;
error_log  logs/rtmp-push-info.log  info;
events {
    worker_connections  1024;
}
stream{
    upstream rmtp-push{
        server 47.52.195.184:1935;
        server 47.75.58.155:1935;
    }
    server{
        listen 1935;
        proxy_pass rmtp-push;
    }
}
http{
    upstream rtmp-http-server{
        server 47.52.195.184:2935;
        server 47.75.58.155:2935;
    }
    server{
        listen 2935;
        location / {
            proxy_pass http://rtmp-http-server;
        }
    }
}
```
----------
B 拉流转发配置rtmp-pull.conf
```
#注意更换目录
load_module /data/nginx/objs/ngx_stream_module.so;
worker_processes  1;
error_log  logs/rtmp-pull-error.log; 
error_log  logs/rtmp-pull-notice.log  notice;
error_log  logs/rtmp-pull-info.log  info;
events {
    worker_connections  1024;
}
stream{
    #拉流负载
    upstream rmtp-pull{
        #拉流ip端口
        server 47.52.195.184:19350;
        server 47.75.58.155:19350;
    }
    server{
        listen 19350;
        proxy_pass rmtp-pull;
    }
}
http{
    #拉流测试地址
    upstream rtmp-http-server{
        server 47.52.195.184:29350;
        server 47.75.58.155:29350;
    }
    server{
        listen 2935;
        location / {
            proxy_pass http://rtmp-http-server;
        }
    }
    
}
```
**注意修改IP以及路径**

## 2 资源服务转发配置

```
worker_processes  1;
events {
        worker_connections  1024;
}
http {
        include       mime.types;
        default_type  application/html;
        log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';
        access_log  /var/log/nginx/access.log  main;
        error_log  /var/log/nginx/error.log  error;
        sendfile        on;
        keepalive_timeout  65;
        client_max_body_size 0; 
        proxy_redirect ~/big/upload/(.*) /big/upload/$1; 
        ## 资源负载均衡
        upstream go-fastdfs {
                ## 资源服务器ip
                server 10.1.14.36:8080;
                server 10.1.14.37:8080;
                ip_hash;
        }
        server {
                listen       8080;
                server_name  localhost;
                location / {
                    proxy_set_header Host $host; 
                    proxy_set_header X-Real-IP $remote_addr;
                    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for; 
                    proxy_pass http://go-fastdfs;
                }

        }
}
```
**注意修改IP以及路径**

##3.node聊天服务代理配置

```
load_module /data/nginx/objs/ngx_stream_module.so;
worker_processes  1;
error_log  logs/rtmp-pull-error.log; 
error_log  logs/rtmp-pull-notice.log  notice;
error_log  logs/rtmp-pull-info.log  info;
events {
    worker_connections  1024;
}
stream{
    #node服务负载
    upstream socket_server{
        #聊天服务ip端口
        server 10.0.0.7:19967;
    }
    server{
        listen 19967;    #此处为nginx监听端口 根据自身情况设置
        server_name a.com;    #域名，如果有域名
        proxy_pass socket_server;    #对应upstream配置的参数{socket_server}
    }
}
```

##4.PHP应用服务配置

```
worker_processes  1;
events {
        worker_connections  1024;
}
http {
        #访问日志配置根据自己的需求配置路径
        access_log  /var/log/nginx/access.log  main;
        error_log  /var/log/nginx/error.log  error;
        ## 资源负载均衡
        upstream live-api {
                ## 应用服务器ip
                server 10.0.0.8;
                server 10.0.0.7;
                ip_hash;
        }
        server {
                listen       80;  #nginx监听端口，根据自身需求配置
                server_name  localhost;  #访问域名
                location / {
                    proxy_set_header Host $host; 
                    proxy_set_header X-Real-IP $remote_addr;
                    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for; 
                    proxy_pass http://live-api;
                }

        }
}
```


