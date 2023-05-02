##gitlab docker  php 项目构建部署
#####大概步骤:
>1、安装部署jenkins

>2、项目代码存放gitlab

>3、jenkins创建构建项目

>4、gitlab 添加webhooks 钩子

>5、git提交代码触发hook，通知jenkins开始构建

>6、jenkins开始构建,从gitlab拉取代码构建出一个镜像

>7、将jenkins构建出的项目推送到远程(本地)仓库

>8、docker 服务器判断是否有存了的容器(容器名判断)，
>如果存在先停止在删除，在判断镜像是否存在，存在也删除

>9、重新拉取新构建的镜像并允许启动容器
