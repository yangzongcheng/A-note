#本地仓库https配置(ip)




[参考链接](https://www.cnblogs.com/andy9468/p/10736214.html)

```text
1、修改openssl.cnf，支持IP地址方式，HTTPS访问
在Redhat7或者Centos系统中，文件所在位置是/etc/pki/tls/openssl.cnf。在其中的[ v3_ca]部分，添加subjectAltName选项：

2
vim /etc/pki/tls/openssl.cnf
在v3_ca模块下添加：subjectAltName
[ v3_ca ]
subjectAltName= IP:129.144.150.111
　　

2、生成证书
创建一个目录： /certs
然后执行：

openssl req -newkey rsa:2048 -nodes -keyout /certs/domain.key -x509 -days 365 -out /certs/domain.crt
　　

Common Name (eg, your name or your server'shostname) []:129.144.150.111
执行成功后会生成：domain.key 和domain.crt 两个文件

 

3、COPY证书到docker系统中
使用Docker Registry的Docker机需要将domain.crt拷贝到 /etc/docker/certs.d/[docker_registry_domain:端口或者IP:端口]/ca.crt，

mkdir -p /etc/docker/certs.d/129.144.150.111:5000
cp /certs/domain.crt /etc/docker/certs.d/129.144.150.111:5000/ca.crt
　　

4、将domain.crt内容放入系统的CA bundle文件当中，使操作系统信任我们的自签名证书。
CentOS 6 / 7或者REDHAT中bundle文件的位置在/etc/pki/tls/certs/ca-bundle.crt：

cat /certs/domain.crt >>/etc/pki/tls/certs/ca-bundle.crt
　　

Ubuntu/Debian Bundle文件地址/etc/ssl/certs/ca-certificates.crt

cat /certs/domain.crt >> /etc/ssl/certs/ca-certificates.crt
　　

注意，如果之前已经有cat过同样的IP, 需要到ca-bundle.crt中把它删除，再做cat操作。否则后面PUSH时会报：

Get https://129.144.150.111:5000/v1/_ping:x509: certificate signed by unknown authority

 

5、重启docker

systemctl restart docker
　　

6、创建启动docker容器：创建一个运行的docker私有仓库容器，端口5000，https访问

docker run -d -p 5000:5000 --name=registry-https5000 -v /certs/:/certs -e REGISTRY_HTTP_ADDR=0.0.0.0:5000 -e REGISTRY_HTTP_TLS_CERTIFICATE=/certs/domain.crt -e REGISTRY_HTTP_TLS_KEY=/certs/domain.key registry
　　

7、验证测试
确认HTTPS OK: 

curl -k https://129.144.150.111:5000/v2
docker run -d -p 5000:5000 --name=my_registry -v /certs/:/certs -e REGISTRY_HTTP_ADDR=0.0.0.0:5000 -e REGISTRY_HTTP_TLS_CERTIFICATE=/certs/domain.crt -e REGISTRY_HTTP_TLS_KEY=/certs/domain.key registry

```