### centos7 docker swarm集群搭建
[链接](https://www.cnblogs.com/straycats/p/8978135.html)
>准备两台服务器

```text
A:111.231.253.204

B:129.204.48.26

A服务器作为:manager

B服务器为 worker 

安装docker
生产环境建议安装docker-ce版本
版本尽量保持一致
且manager 服务器docker版本要大于等于worker服务器docker版本
```
<br>
>设置主机名

```tet
manager节点执行：

hostnamectl --static set-hostname manager01
 

worker节点执行：

hostnamectl --static set-hostname worker01

目的是为了方便查看主机名
[root@VM_10_5_centos ~]# hostnamectl 
   Static hostname: manager01(主机名)
   Pretty hostname: VM_10_5_centos
Transient hostname: VM_10_5_centos
         Icon name: computer-vm
           Chassis: vm
        Machine ID: c28d40cbc8e3adcb4e32d9779a77b39e
           Boot ID: a98aade483d74134bd450aca425b6ddc
    Virtualization: kvm
  Operating System: CentOS Linux 7 (Core)
       CPE OS Name: cpe:/o:centos:centos:7
            Kernel: Linux 3.10.0-862.el7.x86_64
      Architecture: x86-64

```

>搭建

```text
1、在A服务器执行:
docker swarm init

[root@VM_10_5_centos ~]# docker swarm init
Swarm initialized: current node (9u47481p7tuvsiwraiyeaviz1) is now a manager.

To add a worker to this swarm, run the following command:

    docker swarm join --token SWMTKN-1-2alf3pvjigdz02ddhh7lkuze6y9myi2zaaw1zu320e6xgnylxp-34qeu3uxsag2g6c1cj39vn79t 10.5.10.5:2377

To add a manager to this swarm, run 'docker swarm join-token manager' and follow the instructions.

[root@VM_10_5_centos ~]# 

-----------------------------------------注释-----------------------------------------
# 创建集群（当宿主机有多个IP时，需要指定IP）

docker swarm init --advertise-addr 192.168.12.11
创建后，该节点为manager节点（leader）。

如果需要添加其他管理节点，可以使用下面的命令查看作为管理节点加入集群的命令。

docker swarm join-token manager 


注意必须要放行端口 iptables

2377/tcp 管理节点通信端口

7964/tcp/udp 节点之前的通信端口
-----------------------------------------注释-----------------------------------------
```
<hr>
```text
2、工作节点（Worker）添加
# 查看作为工作节点接入集群的命令(此命令只能在manager上运行)
docker swarm join-token worker


得到添加到manager命令：
docker swarm join --token SWMTKN-1-2alf3pvjigdz02ddhh7lkuze6y9myi2zaaw1zu320e6xgnylxp-34qeu3uxsag2g6c1cj39vn79t 10.5.10.5:2377


(注意默认是内网ip,如果不支持则使用外网ip)

使用以下命令在manager查看节点信息
docker node ls 

离开集群(在node节点服务器执行)
docker swarm leave

解散集群(在manager服务器运行,整个服务会解散)
docker swarm leave --force

```
