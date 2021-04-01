##编译安装php8
- 从官网下载php8 linux 版本

[官网地址](https://www.php.net/downloads)

```shell script


./configure --prefix=/usr/local/php/ --enable-debug --enable-fpm --with-config-file-path=/usr/local/php/etc/ --with-config-file-scan-dir=/usr/local/php/etc/php.d/

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

