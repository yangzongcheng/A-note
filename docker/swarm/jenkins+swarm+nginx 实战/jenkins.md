#jenkins 部署

>构建脚本

```shell script
#构建后执 在目标服务器执行

cd /www/jenkins-php/

#构建docker 镜像 将文件打包到镜像内
docker build -t jenkins-php:pro  ./

docker tag    jenkins-php:pro  111.231.253.204:5000/app-t:pro

#推送到私服
docker push  111.231.253.204:5000/app-t:pro

echo "操作成功"
```

>说明

```text
此处指展示了一个脚本

实际上缺少了两个步骤:
1、拉取私服仓库镜像
2、更新服务镜像



思路:
1、继续添加 worker 节点的构建脚本
具体需要判断脚本是否存在,存在需要先删除在拉取

2、等worker节点服务的镜像都拉取完成后,继续添加manager 构建脚本
运行更新镜像命令
```