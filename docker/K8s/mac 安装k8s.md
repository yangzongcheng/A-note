###mac k8s
[参考链接](https://segmentfault.com/a/1190000038167301)

>Docker Kubernetes一直处于Starting？

github:  <https://github.com/AliyunContainerService/k8s-for-docker-desktop>
```text
问题一的时候我点击Enable Kubernetes之后就一直出现Starting，如下：
于是到网上找各种资料，最终解决方式如下，

1、卸载docker(看具体情况)
卸载之后别忘了配置阿里源

{
  "experimental": true,
  "debug": true,
  "registry-mirrors": [
    "https://xxx.mirror.aliyuncs.com"
  ]
}
2、查看hosts

127.0.0.1       localhost
255.255.255.255 broadcasthost
::1             localhost

#Added by Docker Desktop
#To allow the same kube context to work on the host and the container:
127.0.0.1 kubernetes.docker.internal
#End of section
3、删除相关文件

rm -rf ~/.docker
rm -rf ~/.kube
rm -rf ~/Library/Group\ Containers/group.com.docker/pki/
4、安装相关images文件
到k8s-for-docker-desktop上面clone项目，在 Mac 上执行如下脚本，

./load_images.sh
5、安装完成后，重启Docker和Kubernetes等待时间完成即可

```
![](https://segmentfault.com/img/bVcKjcI)

