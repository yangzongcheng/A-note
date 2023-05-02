#k8s基础

###概念
- 什么是k8s？

	```text
	k8s是一组服务器集群
	k8s所管理的集群节点上的容器
	```

- k8s的功能

	```text
	自我修复
	弹性伸缩：实时根据服务器的并发情况，增加或缩减容器数量
	自动部署
	回滚
	服务发现和负载均衡
	机密和配置共享管理
	```

- k8s集群分为两类节点

	```text
	master node :主
	worker node :工作
	```

- master 节点的组件(程序)

	```
	apiserver：接受客户端操作可k8s的指令
	schduler：从多个worker node节点的组件中选举一个来启动服务
	controller manager：向worker节点的kubelet 发送指令
	```
- node节点的组件（程序）

	```
	kubelet：向docker发送指令管理docker容器
	kubeproxy：管理docker容器的网络
	```		
- etcd
	```
	k8s 的数据库
	用来注册节点、服务、记录账户····
	```	

###核心概念
- pod

	```
	pod是k8s最小部署单元
	一个pod中可以有一个或者多个容器或一组容器
	pod又称为容器组
	```
- Controllers：控制器，控制pod，启动、停止、删除

	```
	ReplicaSet
	Deployment
	StatefulSet
	Job
	Cronjob
	```
	
- service：服务

	```
	将一组pod关联起来，提供一个统一入口
	即使pod地址发送改变，这个统一入口也不会变化，可以保证用户访问不收影响
	```	
- label：标签

	```
	一组pod是一个统一的标签
	service是通过标签和一组pod进行关联的
	```	
- namespace：命名空间
	```
	用来隔离pod的运行环境【默认情况pod可以相互访问】
	使用场景：
	 		为不同的公司提供隔离的pod运行环境
	 		为开发环境、测试环境、生产环境分别准备不同的命名空间，进行隔离
	```	
	
<br>	
<hr>
###k8s架构
> 架构类别

- 单master  - 测试环境
- 多master  - 生产环境	

>生产环境k8s规划

- master建议3台
- etcd建议3台(如3、5、7)，必须为奇数(选举)

> 实验环境规划
	
```
3台服务器
2G内存
2核CPU
```

> 至少需要服务器

```text
load balaner
数量：2
作用：负责worker 节点服务和master节点服务通讯负载均衡

etcd
数量：3
作用：存储数据，需要选举所以至少3台

master node
数量:2
作用：主节点，用2台是因为挂了一个还有一个保证服务不中断

worker node
数量：2
作用：工作节点，至少两个才能满足测试


所以生产环境至少9台服务器
```

<br>
	<hr>
	
###部署单master集群
- 集群规划

	```
	master
	hostname：k8s-master1
	IP：192.168.31.63
	
	worker1
	hostname：k8s-node1
	IP：192.168.31.65
	
	worker2
	hostname：k8s-node2
	IP：192.168.31.66
	
	k8s版本：1.16
	安装方式：离线-二进制
	操作系统：centos7.7
	
	```	

- 初始化服务器

	```
	1、关闭防火墙
	systemctl stop firewalld
	systemctl disble firewalld
		
	2、关闭selinux
	临时关闭：输入命令setenforce 0，重启系统后还会开启。
	永久关闭：输入命令vi /etc/selinux/config，将SELINUX=enforcing改为SELINUX=disabled，
	然后保存退出。
		
	注意：ks8需要永久关闭selinux
		
	3、配置主机名
	hostnamectl   set-hostname  主机名
		
	4、配置名称解析
	vim /etc/hosts
	添加
	192.168.31.63  k8s-master1
	192.168.31.64  k8s-master2
	192.168.31.65  k8s-node1
	192.168.31.65  k8s-node2
	注意：所有的主节点和从节点都要添加
		
	5、配置时间同步
	具体请百度
	作用：使所有节点的时间完全一致
	原理：使用一台master作为时间服务器，其他服务器的时间都来同步这台服务器
		
	6、关闭交换分区
	swapoff -a
	vim /etc/fstab
	删除一行
	检查：free -m
		
		
	```
	
	<br>
	<hr>

###内部通讯ssl实现https
> 使用cfssl生成证书

<br>
	<hr>
###给etcd颁发证书
- 1、创建证书颁发机构   

  ```
  创建成功后会生成两个文件：
	  ca-key.pem(公钥)
	  ca.pem(证书，可以理解为私钥)
  
  ```

- 2、填写表单，写明etcd所在节点的ip

	```
	etcd所在node ip:
	192.168.1.10
	192.168.1.11
	192.168.1.12
	```

- 3、向证书颁发机构申请证书

	```
	使用刚刚生成的公钥私钥文件 向刚刚配置ip的服务器颁发证书
	
	颁发成功后会再次生成两个文件：
	server-key.pem
	server.pen
	这两个文件属于etcd服务器证书
	
	```
- 具体步骤
	
	```
	第一步：上传TLS安装包
	传到/root下
	略
	
	第二步：
	# tar xvf /root/TLS.tar.gz
	# cd /root/TLS
	# vim server-csr.json
	修改host中的IP地址，这里的IP是etcd所在节点的IP地址
	
	{
	    "CN": "etcd",
	    "hosts": [
	        "192.168.1.10",
	        "192.168.1.11",
	        "192.168.1.12"
	    ],
	    "key": {
	        "algo": "rsa",
	        "size": 2048
	    },
	    "names": [
	        {
	            "C": "CN",
	            "L": "BeiJing",
	            "ST": "BeiJing"
	        }
	    ]
	}
	
	#./generate_etcd_cert.sh
	
	验证
	#ls *pem
	ca-key.pem 
	ca.pem 
	server-key.pem 
	server.pem
	
	如果存在以上几个文件则颁发成功
	```	
	<br>
	<hr>
###部署etcd
- etcd至少需要三台虚拟机

	```
	在三台服务器上分别按照etcd
	ip:
	192.168.1.10
	192.168.1.11
	192.168.1.12
	```
	```
	解压之后会生成一个文件和一个目录
	# tar xvf etcd.tar.gz
	
	#添加到服务
	# mv etcd.service /usr/lib/systemd/system
	
	
	# vim /opt/etcd/cfg/etcd.conf
	
	#[Member] 单机
	#服务名不能和集群重名
	ETCD_NAME="etcd-1" 
	#数据存放地址
	ETCD_DATA_DIR="/var/lib/etcd/default.etcd" #shuju 
	#集群通讯端口
	ETCD_LISTEN_PEER_URLS="https://192.168.1.10:2380"
	#对外端口
	ETCD_LISTEN_CLIENT_URLS="https://192.168.1.10:2379"
	
	#[clustering] 集群
	ETCD_INITIAL_ADVERTISE_PEER_URLS="https://192.168.1.10:2380"
	ETCD_ADVERTISE_CLIENT_URLS="https://192.168.1.10:2379"
	ETCD_INITIAL_CLUSTER="etcd-1=https://192.168.1.10:2380,etcd-2=https://192.168.1.11:2380,etcd-3=https
	://192.168.1.12:2380"
	ETCD_INITIAL_CLUSTER_TOKEN="etcd-cluster"
	#new 代表新的集群  其他参数可能代表加入已有集群
	ETCD INITIAL CLUSTER STATE="new"
	```
	```
	证书
	# rm -rf /opt/etcd/ssl/*
	# \cp -fv ca.pem server.pem server-key.pem/opt/etcd/ssl/
	```
	```
	
	将etc管理程序和程序目录发送到node1和node2
	# scp /usr/lib/systemd/system/etcd.service root@k8s-node1:/usr/lib/systemd/system/
	# scp /usr/lib/systemd/system/etcd.service root@k8s-node2:/usr/lib/systemd/system/
	# scp -r /opt/etcd/ root@k8s-node2:/opt/
	# scp -r /opt/etcd/ root@k8s-node1:/opt/
	```
	```
	
	在node1(192.168.1.11)上修改etcd的配置文件
	vim /opt/etcd/cfg/etcd. conf
	# [Member l
	ETCD NAME="etcd-2
	ETCD DATA DIR="/var/lib/etcd/ default.etcd
	Etcd_LiSten Peer Urls=httPs: //192.168.1.11: 2380
	Etcd_liSten ClieNt UrlS=htTps: //192.168.1.11: 2379
	#[Clustering]
	Etcd IniTial AdveRtisE Peer Urls=Https: //192.168.1.11: 2380
	Etcd_adVerTise_ clIenT_ Urls="https: //192.168.1.11: 2379
	Etcd_inItiaLclusteR="etcd-1=Https://192.168.1.10:2380,etcd-2=https://192.168.1.11:2380,etcd-3=https://
	192.168.1.12:2388"
	ETCD INITIAL CLUSTER TOKEN="etcd-cluster
	ETCD INITIAL CLUSTER STATE="new
	```
	
	```
	
	在node1(192.168.1.12)上修改etcd的配置文件
	vim /opt/etcd/cfg/etcd. conf
	# [Member l
	ETCD NAME="etcd-3
	ETCD DATA DIR="/var/lib/etcd/ default.etcd
	Etcd_LiSten Peer Urls=httPs: //192.168.1.12: 2380
	Etcd_liSten ClieNt UrlS=htTps: //192.168.1.12: 2379
	#[Clustering]
	Etcd IniTial AdveRtisE Peer Urls=Https: //192.168.1.12: 2380
	Etcd_adVerTise_ clIenT_ Urls="https: //192.168.1.12: 2379
	Etcd_inItiaLclusteR="etcd-1=Https://192.168.1.10:2380,etcd-2=https://192.168.1.11:2380,etcd-3=https://
	192.168.1.12:2388"
	ETCD INITIAL CLUSTER TOKEN="etcd-cluster
	ETCD INITIAL CLUSTER STATE="new
	
	```
	```
	
	
	在三个节点一次后动etcd服务
	systemctl start etcd
	#设置为开机启动
	sysitemctl enable etcd
	
	注意以上命令第一台机器启动时会卡住，是因为其他集群机器还未启动正在找机器，这时继续启动其他机器就会启动成功
	
	检查是否后动成功(在任意机器上执行)
	/opt/etcd /bin/etcdctl --ca-file=/opt/etcd/ssl/ca pem --cert-file=/opt/etcd/ssl/server pem
	key-file=/opt/etcd/ssl/server-key pem
	endpoints="https://192.168.1.10:2379,https://192.168.1.11:2379,https://192.168.1.12:2379
	
	#输出以下结果证明集群健康
	cluster-health
	
	
	```
	
	<br>
	<hr>

	###为 ap1 server签发证书
	-
	
	```
	cd /root/TLS/k8s/
	#./generate k8s cert. sh
	```
	
	<br>
	<hr>
	
	### 部署 master服务
	
	- cp文件
	
	```
	tar xvf k8s-master. tar.gz
	mv kube-apiserver service kube-controller-manager service kube-scheduler. service /usr/lib/systemd/system/
	
	#这应该是个空目录移动到opt下
	mv kubernetes /opt/
	cp /root/TLS/k8s/ica*pem, server pem, server-key. pem)/opt/kubernetes/ss1/-rvf
	
	```
	
	- 修改 apiserver的配置文件
	
	```
	apiserver ip 192.168.1.13
	修改 apiserver的配置文件
	# vim /opt/kubernetes/cfg/kube-apiserver.conf
	
	KUBE APISERVER OPTS="--logtostderr=false
	V=2
	log-dir=/opt/kubernetes/logs
	#etcd 集群
	retcd-servers=https://192.168.31.63:2379,https://192.168.31.65:2379,https://192.168.31.66:2379
	#apiserver ip 192.168.1.13
	-bind-address=192.168.1.13 
	-secure-port=6443
	apiserver ip 192.168.1.13
	advertise-address=192.168.1.13
	allow-privileged=true
	service-cluster-ip-range=10.0.0.0/24\
	enable-admission-plugins=NamespaceLifecycle, LimitRanger, ServiceAccount, ResourceQuota, NodeRestriction
	authorization -mode=RBAC, Node
	enable-bootstrap-token-authetrue
	-token-auth-file=/opt/kubernetes/cfg/token.csv
	service-node-port-range=30000-32767\
	
	#kubelet 相关证书
	kubelet-client-certificate=/opt/kubernetes/ssl/server.pem
	kubelet-client-key=/opt/kubernetes/ssl/server-key.pem
	
	#apiserver https证书
	-tIs-cert-file=/opt/kubernetes/ssl/server.pem
	-tls-private-key-file=/opt/kubernetes/ssl/server-key.pem
	client-ca-file=/opt/kubernetes/ssl/ca.pem
	service-account-key-file=/opt/kubernetesssl/ca-key.pem
	
	#访问etcd的证书
	etcd-cafile=/opt/etcd/ssl/ca.pem
	-etcd-certfile=/opt/etcd/ssl/server.pem
	etcd-keyfile=/opt/etcd/ssl/server-key.pem
	
	-audit-log-maxage=30
	- audit-log-maxbackup=3
	audit-log-maxsize=100
	-audit-log-path=/opt/kubernetes/logs/k8s-audit log"
	
	```
	- kube-controller-manager.conf 配置
	
	```
	KUBE CONTROLLER MANAGER OPTS="--logtostderr=false
	V=2
	log-dir=/opt/kubernetes/logs
	
	#如果有多个apiserver 会自动选择一个调度
	leader-elect=true
	
	#指定的apiserver 的地址 如果需要外部访问不能写127.0.0.1
	-- master=127.8.8.1:8988
	
	#服务所监听的地址
	-- address=127.8.8.1\
	#是否开启网络插件，必须开
	-allocate-node -cidrsstrue
	#网段范围
	--c1 uster-cidr=10.244.8.8/16
	
	#客户端分配ip 地址范围
	service-cluster-ip-range=10.0.0.0/24\
	
	#以下为证书相关
	cluster-signing-cert-file=/opt/kubernetes/ssl/ca.em V
	cluster-signing-key-file=/opt/kubernetes/ssl/ca-key.pem
	root-ca-file=/opt/kubernetes/ssl/ca.pem
	service-account-private-key-file=/opt/kubernetes/ssl/ca-key.pem
	#证书有效期时间
	experimental-cluster-signing-duration=87600h0mes
	```
	- 后动 master
	
	```
	后动 master
	systemctl start kube-apiserver
	systemctl enable kube - apiserver
	systemctl enable kube -scheduler
	systemctl start kube-scheduler
	systemctl start kube-controller-manager
	systemctl start kube -scheduler
	systemctl enable kube-controller-manager
	
	cp /opt/kubernetes/bin/kubectl /bin/
	检查动结果
	ps aux grep kube
	ps aux grep kube wc-4
	kubectl get cs
	
	
	配置t1s基于 bootstrap自动颁发证书
	kubectl create clusterrolebinding kubelet-bootstrap
	clusterrole=system: node-bootstrapper
	user=kubelet-bootstrap
	```
	
