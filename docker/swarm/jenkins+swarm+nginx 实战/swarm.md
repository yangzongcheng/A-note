#swarm 集群

>服务器

```text
准备三台服务器:

主:manager
111.231.253.204

worker1
129.204.48.26

worker2
121.4.94.84

创建服务
docker service create  --replicas 3  --name app-t -p 8081:80 111.231.253.204:5000/app-t:pro

更新镜像
docker service update --image 111.231.253.204:5000/app-t:pro  app-t

注意每台服务器都必须有此镜像存在
```
>节点
![](./img/WX20201214-143230@2x.png)

>服务
![](./img/WX20201214-143230@2x.png)