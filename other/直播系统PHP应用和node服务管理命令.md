# 直播系统PHP应用和node服务管理命令

标签（空格分隔）： 直播系统

---
##直播应用服务器Nginx管理

```
#启动服务
service nginx start | systemctl start nginx

#停止服务
service nginx stop | systemctl stop nginx

#重启服务
service nginx restart | systemctl restart nginx

#查看服务状态
service nginx status | systemctl status nginx
```

##PHP应用服务管理
```
#启动服务
service php-fpm start | systemctl start php-fpm

#停止服务
service php-fpm stop | systemctl stop php-fpm

#重启服务
service php-fpm restart | systemctl restart php-fpm

#查看服务状态
service php-fpm status | systemctl status php-fpm
```
##Node服务管理

![image.png-18.3kB][1]
![image.png-21.7kB][2]
```
#查看服务状态 
pm2 list #结果如上图  status为online表示正常，stopped表示已停止，没有表示没有启动服务

#启动服务 --watch 检测文件改动自动重启服务
pm2 start s1.js --watch #需要进入到s1.js的存放目录，否则请使用绝对路径 如：/data/wwwroot/live/LiNewIM/s1.js
pm2 start exposure.js --watch  #同上

#停止服务
pm2 stop s1 #s1为服务的名称 对应上图中的name

#重启服务
pm2 restart s1 --watch  #重新启动服务并检测文件变化
```


  [1]: http://static.zybuluo.com/renheng/ixelr3l781m1j8xnjy1f9dhx/image.png
  [2]: http://static.zybuluo.com/renheng/f92y3zz0p67ekijq6k2ipm45/image.png