#https 实现 域名
[参考链接](https://blog.csdn.net/xcjing/article/details/70238273/)

<font size=4 color=red>
域名：wwiue.com
</font>

>证书配置

```text
openssl req -newkey rsa:2048 -nodes -sha256 -keyout /certs/domain.key -x509 -days 365 -out /certs/domain.crt



[root@manager01 certs]# openssl req -newkey rsa:2048 -nodes -sha256 -keyout /certs/domain.key -x509 -days 365 -out /certs/domain.crt
Generating a 2048 bit RSA private key
.........................................................................................................+++
..............................................+++
writing new private key to '/certs/domain.key'
-----
You are about to be asked to enter information that will be incorporated
into your certificate request.
What you are about to enter is what is called a Distinguished Name or a DN.
There are quite a few fields but you can leave some blank
For some fields there will be a default value,
If you enter '.', the field will be left blank.
-----
Country Name (2 letter code) [XX]:
State or Province Name (full name) []:
Locality Name (eg, city) [Default City]:
Organization Name (eg, company) [Default Company Ltd]:
Organizational Unit Name (eg, section) []:
Common Name (eg, your name or your server's hostname) []:wwiue.com
Email Address []:

(注意上面有一步填域名的操作)


将domain.crt内容放入系统的CA bundle文件当中，使操作系统信任我们的自签名证书。
cp /certs/domain.crt /etc/docker/certs.d/wwiue.com:5000/ca.crt

CentOS 6 / 7中bundle文件的位置在/etc/pki/tls/certs/ca-bundle.crt：
cat domain.crt >> /etc/pki/tls/certs/ca-bundle.crt



Ubuntu/Debian Bundle文件地址/etc/ssl/certs/ca-certificates.crt
cat domain.crt >> /etc/ssl/certs/ca-certificates.crt

```

>生成仓库容器

```text
 docker run -d -p 5000:5000 \
 --name my_registry  \
 --privileged=true \
 -v /opt/registry:/tmp/registry  \
 -v /certs/:/root/certs \
 -e REGISTRY_HTTP_TLS_CERTIFICATE=/root/certs/domain.crt \
 -e REGISTRY_HTTP_TLS_KEY=/root/certs/domain.key \
  registry 

```