##编译安装php8
- 从官网下载php8 linux 版本

[官网地址](https://www.php.net/downloads)

```shell script


./configure --prefix=/usr/local/php/ --enable-debug --enable-fpm --with-config-file-path=/usr/local/php/etc/ 

--with-config-file-scan-dir=/usr/local/php/etc/php.d/



注意参数目录


可能出现的错误
--with-iconv=/usr/local/opt/libiconv/

```

make 
make install

编译安装完成之后,需要从解压后的文件夹中把php.ini-development
或是 php.ini-production 重命名成php.ini 复制到php安装文件中的相应位置,
可在phpinfo中查看 相应位置.
把/usr/local/php/etc/php-fpm.conf.default 复制到当前文件夹下,保存为php-fpm.conf

```text
参数说明

指定php.ini位置
--with-config-file-path=/usr/local/php/etc 


指定 php 安装目录
--prefix=/usr/local/php


```

```text
整合到MxSrvs
1、 /Applications/MxSrvs/bin 下创建 一个空版本
2、切换到这回空版本(需要重启MxSrvs)
3、编译安装，编译时目录选择 /Applications/MxSrvs/bin/php
4、调整php-fpm
```
./configure --with-openssl --with-php-config=/Applications/MxSrvs/bin/php/bin/php-config

--enable-fpm  fpm

查看帮助
./configure --help

知道ini位置
--with-config-file-path=/Applications/MxSrvs/bin/php/etc 

//扫描配置文件的路径
--with-config-file-scan-dir=/Applications/MxSrvs/bin/php/etc/php.d/



./configure  --prefix=/Applications/MxSrvs/bin/php \
--with-iconv=/usr/local/opt/libiconv/   \
--with-curl  --with-xml   --enable-opcache  --enable-fpm  \
--with-config-file-path=/Applications/MxSrvs/bin/php/etc  \
--with-config-file-scan-dir=/Applications/MxSrvs/bin/php/etc/php.d/
--enable-mysqlnd 



./configure \
--prefix=/Applications/MxSrvs/bin/php \
--with-config-file-path=/Applications/MxSrvs/bin/php/etc \
--with-config-file-scan-dir=/Applications/MxSrvs/bin/php/etc/conf.d \
--with-iconv=/usr/local/opt/libiconv/   \
--with-curl \
--with-xml \
--enable-fpm \
--enable-opcache \
--enable-pdo \
--enable-xml \
--with-mysqli \
--with-pdo-mysql \
--with-zlib \
--enable-zip \
--enable-mbstring \
--enable-ftp \
--with-gd \
--enable-mysqlnd \
--with-openssl-dir=/usr/local/opt/openssl@1.1/  \
--with-openssl \



如果装不上去请尝试多个版本有的版本有毒就是装不上去

7版本建议装7.4.20 版本

yaml 扩展安装
pecl install yaml

安装扩展的最简单的办法就是去pecl 官网搜索下载下来自己编译
